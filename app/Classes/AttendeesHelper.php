<?php
namespace App\Classes;
use App\Models\Attendee;
use App\Models\Ceremony;
use App\Models\Recipient;
use App\Models\Guest;
use App\Classes\MailHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use DateTime;
use DateInterval;
use DateTimeZone;

class AttendeesHelper
{

  /**
  * Get ceremony from list of attendees
  *
  * @return Array
  */

  private function getAttendeeByCeremonyID($attendable, int $ceremony_id) {
    $attendees = $attendable->attendee()->get()->toArray();
    return current(array_filter($attendees, function($attendee) use($ceremony_id) {
      return isset($attendee['ceremonies_id']) && $ceremony_id == $attendee['ceremonies_id'];
    }));
  }

  /**
  * Get assigned or invited ceremony for attendee
  *
  * @return Array
  */

  public function getAssignedAttendee($attendable) {
    $attendees = $attendable->attendee()->get()->toArray();
    return current(array_filter($attendees, function($attendee) {
      return isset($attendee['status'])
      && (
        'assigned' == $attendee['status']
        || 'invited' == $attendee['status']
        || 'expired' == $attendee['status']
      );
    }));
  }

  /**
  * Get top level status for attendee
  *
  * @return Array
  */

  public function getStatus($attendable) {
    $attendees = $attendable->attendee()->get()->toArray();
    $status = '';
    return current(array_filter($attendees, function($attendee) {
      if (empty($status) || $attendee['status'] !== 'waitlisted') {
        $status = $attendee['status'];
      }
    }));
    return $status;
  }

  /**
  * Refresh expired status for all attendees
  *
  * @return Array
  */

  public function refresh() {

    $attendees = Attendee::all();
    // lookup RSVP token for given key and token
    foreach ($attendees as $attendee) {
      if ($attendee->status === 'invited' && empty(Cache::get($attendee->id))) {
        $attendee->status = 'expired';
        $attendee->save();
      }
    }
  }

  /**
  * Check if RSVP token is active
  *
  * @return Array
  */

  public function checkRSVP(Attendee $attendee, string $token) {

    // lookup RSVP for given key and token
    $storedToken = Cache::get($attendee->id);
    return $storedToken === $token;
  }

  /**
  * Activate RSVP token for set expiration
  *
  * @return Array
  */

  public function activateRSVP(Attendee $attendee) {

    // set expiry date for +1 hour
    $expiry = new DateTime();
    $expiry->setTimezone(new DateTimeZone('America/Vancouver'));
    $expiry->add(new DateInterval('PT1H'));

    // pull RSVP token for given attendee
    $token = Cache::pull($attendee->id);
    // push token with new expiration
    Cache::put($attendee->id, $token, $expiry);

    Log::info('RSVP Activated',
    array('id' => $attendee->id, 'stored token' => $token, 'token' => $token));

    // format scheduled date and time
    $scheduled_datetime = new DateTime($attendee->ceremonies->scheduled_datetime);
    $scheduled_datetime->setTimezone(new DateTimeZone('America/Vancouver'));


    // token is valid and active
    return array(
      'recipient' => $attendee->attendable,
      'scheduled_datetime' => $scheduled_datetime->format('g:ia \o\n l jS F Y'),
      'expiration' => $expiry->format('g:ia \o\n l jS F Y')
    );

  }

  /**
  * Clear RSVP token
  *
  * @return Array
  */

  public function clearRSVP(Attendee $attendee) {
    Cache::pull($attendee->id);
  }

  /**
  * Attach attendee accommodations
  *
  * @return Array
  */

  public function setAccommodations(Attendee $attendee, Array $accommodations) {

    // clear existing accommodations for attendee
    $attendee->accommodations()->detach();

    // iterate over accommodation types
    foreach ($accommodations as $type => $selections) {
      Log::info('Set Accommodations',
      array('type' => $type, 'data' => $selections));

      // detach existing accommodations and update new ones
      $attendee->accommodations()->syncWithoutDetaching($selections);

    }
    return $attendee;
  }

  /**
  * Attach guest attendee to recipient
  *
  * @return Array
  */

  public function addGuest(Recipient $recipient, Attendee $recipientAttendee, Array $options) {

    // remove any existing guests
    self::removeGuests($recipient);

    // create new guest
    $guest = new Guest([
      'first_name' => 'Guest',
      'last_name' => '',
      'recipient_id' => $recipient->id
    ]);
    $guest->save();

    // assign guest as attendee to ceremony
    $guestAttendee = $this->create('attending', $recipientAttendee->ceremonies_id);

    // ==========================
    Log::info('Guest Attendee',
    array(
      'guestAttendee' => $guestAttendee,
      'guest' => $guest,
      'recipient' => $recipientAttendee->attendable_id,
      'recipientAttendee' => $recipientAttendee
    ));

    $guest->attendee()->save($guestAttendee);

    // send guest confirmation email
    // $mailer = new MailHelper();
    // $mailer->sendConfirmation($attendable, $ceremony, $attendee, $token, $expiry);

    // iterate over accommodation types
    foreach ($options as $type => $selections) {
      // detach existing accommodations and update new ones
      $guestAttendee->accommodations()->syncWithoutDetaching($selections);

    }
    return $guestAttendee;
  }

  /**
  * Clear guest attendees and records attached to recipient
  *
  * @return Array
  */

  public function removeGuests(Recipient $recipient) {
    // lookup guest by recipient
    $guests = Guest::where('recipient_id', '=', $recipient->id)->get();

    Log::info('RSVP', array('guests' => $guests));

    foreach ($guests as $guest) {
      // get all attendee records for guest record
      $attendees = Attendee::where('attendable_id', '=', $guest->id)->get();
      // clear all accommodations attached to guest
      foreach ($attendees as $attendee) {
        $attendee->accommodations()->detach();
      }
      // delete guest attendee
      $guest->attendee()->delete();
      // delete guest
      $guest->delete();
    }
  }

  /**
  * Create new attendee with assignment
  *
  * @return Array
  */

  private function create(string $status, int $ceremony_id) {
    return new Attendee([
      'status' => $status,
      'ceremonies_id' => $ceremony_id
    ]);
  }

  /**
  * Assign attendance status to attendee
  * - Statuses: waitlisted | assigned | invited | attending | declined
  *
  * @return Array
  */
  public function assignStatus($attendable, string $status, int $ceremony_id = 0) {

    // apply rules of status assignment
    switch ($status) {

      case 'assigned':
        // clear all existing assignments
        $attendable->attendee()->delete();
        // assign attendee to ceremony
        $attendee = $this->create('assigned', $ceremony_id);
        $attendable->attendee()->save($attendee);
      break;

      case 'waitlisted':
        // check attendee status
        $status = $this->getStatus($attendable);

        // check that attendee can waitlist
        if ($status === 'invited' || $status === 'declined' || $status === 'attending') {
          return response()->json([
                'errors' => "Attendee cannot be waitlisted for this ceremony",
            ], 500);
        }

        // get attendance record for requested ceremony
        $attendee = Attendee::where([
          'attendable_id' => $attendable->id,
          'ceremonies_id' => $ceremony_id
        ])->first();

        // waitlist attendee if not already set
        if ( empty($attendee) ) {
          $attendee = $this->create('waitlisted', $ceremony_id);
          $attendable->attendee()->save($attendee);
        }
      break;

      case 'invited':
        // get assigned ceremony (must be unique)
        $assignedAttendee = $this->getAssignedAttendee($attendable);
        $ceremony = Ceremony::with('locationAddress')->find($assignedAttendee['ceremonies_id']);

        // clear all existing attendees
        $attendable->attendee()->delete();

        // set attendee as invited to ceremony
        $attendee = $this->create('invited', $assignedAttendee['ceremonies_id']);
        $attendable->attendee()->save($attendee);

        // generate and store RSVP access token
        // - set expiration in two weeks (2 x 604800 seconds)
        $token = Str::random(60);
        // set expiry date for +14 days
        $expiry = new DateTime();
        $expiry->setTimezone(new DateTimeZone('America/Vancouver'));
        $expiry->add(new DateInterval('P14D'));
        Cache::put($attendee->id, $token, $expiry);

        // send invitation email
        $mailer = new MailHelper();
        $mailer->sendInvitation($attendable, $ceremony, $attendee, $token, $expiry);

      break;

      case 'reset':
      /*
        * Remove the attendee record and any attachments
      */

        // get all attendee records for attendable record
        $attendees = Attendee::where('attendable_id', '=', $attendable->id)->get();

        // clear all accommodations attached to attendees
        foreach ($attendees as $attendee) {
          $this->clearRSVP($attendee);
          $attendee->accommodations()->detach();
        }
        // clear all attendee records
        $attendable->attendee()->delete();

        // remove any guests
        self::removeGuests($attendable);

      break;

      default:
      // code...
      break;
    }
    return $attendable;
  }

}
