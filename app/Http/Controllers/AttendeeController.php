<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendee;
use App\Models\Accommodation;
use App\Models\Recipient;
use App\Models\Award;
use App\Models\Organization;
use App\Models\Ceremony;
use App\Classes\AttendeesHelper;
use App\Classes\AddressHelper;
use App\Classes\MailHelper;
use Illuminate\Support\Facades\Log;

class AttendeeController extends Controller
{

  /**
  * Displays a list of all attendies
  *
  * @param Attendee $attendee
  */

  public function index () {
    $this->authorize('viewAny', Attendee::class);
    return Attendee::all();
  }

  /**
  * Retrieve full record of attendee.
  *
  * @param Attendee $attendee
  */

  public function show (Attendee $attendee) {

    $this->authorize('view', Attendee::class);

    // include ceremony and accommodation data
    $accommodations = Attendee::where('id', $attendee->id)
    ->with(['accommodations', 'ceremonies'])
    ->first();

    // get the recipient record
    $recipient = Recipient::where('recipients.id', $attendee->attendable_id)->firstOrFail();

    // include guest record (if exists)
    $guest = isset($recipient->guest->id)
      ? Attendee::where([
          ['attendable_id', '=', $recipient->guest->id],
          ['attendable_type', '=', 'App\Models\Guest']
        ])
        ->with(['accommodations'])
        ->first()
      : null;

    $attendee->recipient = $recipient;
    $attendee->guest = $guest;
    $attendee->accommodations = isset($accommodations->accommodations)
    ? $accommodations->accommodations
    : [];
    $attendee->ceremony = Ceremony::find($attendee->ceremonies_id);
    return $attendee;

  }

  /**
  * Retrieve attendees of requested ceremony.
  *
  * @param Ceremony $ceremony
  */

  public function getByCeremony (Ceremony $ceremony) {
    $this->authorize('view', Ceremony::class);

    $attendees = Attendee::where('ceremonies_id', $ceremony->id)
    ->with(['ceremonies', 'accommodations', 'attendable']);

    // filter recipients + include other attendees to recipient records
    $recipients = (clone $attendees)->recipients()->get();
    foreach ($recipients as $recipient) {
      $attachedAttendees = Attendee::where([
        ['attendable_id', '=', $recipient->attendable_id],
        ['attendable_type', '=', 'App\Models\Recipient']
      ])
      ->with(['ceremonies'])
      ->get();
      $recipient->attendee = $attachedAttendees;
    }
    // filter for guests
    $guests = (clone $attendees)->guests()->get();

    // get recipient/guest counts
    $guest_count = (clone $attendees)
    ->where('attendable_type', '=', 'App\Models\Guest')
    ->count();
    $recipient_count = (clone $attendees)
    ->where('attendable_type', '=', 'App\Models\Recipient')
    ->count();

    return array(
      'ceremony' => $ceremony,
      'recipients' => $recipients,
      'guests' => $guests,
      'total_guests' => $guest_count,
      'total_recipients' => $recipient_count,
      'total_attendees' => $recipient_count + $guest_count
    );

  }

  /**
  * Update attendee record (admin-only)
  *
  * @param Request $request
  * @param Attendee $attendee
  */

  public function update (Request $request, Attendee $attendee) {
    $this->authorize('update', Attendee::class);

    // get recipient record
    $recipient = Recipient::where('id', '=', $attendee->attendable_id)->firstOrFail();

    // attendee must be attending ceremony to edit attendee details
    if (!$attendee->status === 'attending') {
      return response()->json(['error' => 'Invalid'], 422);
    }

    $attendeeHelper = new AttendeesHelper();

    // recipient accepted the invitation
    $attendeeHelper->setAccommodations($attendee, $request->input('recipient'));
    $attendee->save();

    Log::info('update guest', array('has' => $request->input('has_guest'), 'guest' => $request->input('guest')));

    // update guest data (if requested)
    $attendeeHelper->removeGuests($recipient);
    if ($request->input('has_guest')) {
      $attendeeHelper->addGuest($recipient, $attendee, $request->input('guest'));
    }
    return $attendee;
  }

  /**
  * get RSVP attendee info for requested ceremony.
  *
  * @param string $key
  * @param string $token
  */

  public function getRSVP (Attendee $attendee, string $token) {

    // check if RSVP token is active/valid
    $attendeeHelper = new AttendeesHelper();
    $isActive = $attendeeHelper->checkRSVP($attendee, $token);

    if ($isActive) {
      return $attendeeHelper->activateRSVP($attendee);
    }
    else {
      // token has expired or is invalid
      return response()->json(['error' => 'Expired'], 422);
    }

  }

  /**
  * set RSVP attendee and accommodations for requested ceremony.
  * Request Data:
  * - recipient_options:
  *   -- accessibility: Array of IDs
  *   -- dietary: Array of IDs
  * - guest: Boolean
  * - guest_options:
  *   -- accessibility: Array of IDs
  *   -- dietary: Array of IDs
  * - retirement: Boolean
  * - contact:
  *   -- prefix
  *   -- street_address
  *   -- community
  *   -- postal_code
  *
  * @param Request $request
  * @param Attendee $attendee
  * @param string $token
  */

  public function setRSVP (Request $request, Attendee $attendee, string $token) {

    $attendeeHelper = new AttendeesHelper();

    // ensure RSVP token is valid
    $isActive = $attendeeHelper->checkRSVP($attendee, $token);

    if (!$isActive) {
      // token has expired or is invalid
      return response()->json(['error' => 'Expired'], 422);
    }

    $attending = $request->input('attending');
    $addressHelper = new AddressHelper();
    $recipient = Recipient::where('id', '=', $attendee->attendable_id)->firstOrFail();
    $hasGuest = $request->input('data.guest');
    $isRetiring = $request->input('data.retirement');

    if ($attending) {
      // recipient accepted the invitation
      $attendeeHelper->setAccommodations($attendee, $request->input('data.recipient_options'));
      $attendee->status = 'attending';
      $attendee->save();

      // handle added guest and options
      if ($hasGuest) {
        $attendeeHelper->addGuest($recipient, $attendee, $request->input('data.guest_options'));
      }
      else {
        $attendeeHelper->removeGuests($recipient);
      }

      if ($isRetiring) {
        // update forwarding address
        $addressHelper->attachRecipient($recipient, $request->input('data.contact'));
        // set the retirement date
        $recipient->retirement_date = $request->input('data.retirement_date');

        $recipient->save();
        // handle retirement
        Log::info(
          'Retirement', array(
            'is_retiring' => $isRetiring,
            'date' => $request->input('data.retirement_date'),
            'recipient' => $recipient)
          );
      }
    }
    else {
      // recipient declined the invitation
      // - save forwarding address info
      $addressHelper->attachRecipient($recipient, $request->input('data.contact'));
      $attendee->status = 'declined';
      $attendee->save();
    }

    // send confirmation email
    $mailer = new MailHelper();
    $mailer->sendRSVPConfirmation($recipient, $attendee);

    // invalidate RSVP token
    $attendeeHelper->clearRSVP($attendee);
    return $attendee;
  }

  /**
  * Delete record of attendee.
  *
  * @param Attendee $attendee
  */

  public function destroy (Request $request, Attendee $attendee) {
    $this->authorize('delete', Attendee::class);
    $attendee->delete();
    return $attendee;
  }

  /**
  * Generates a PDF for printing the list of recipients by ID;
  *
  * @param Attendee $attendee
  * @return \Illuminate\Http\Response
  */

  public function awardsPresentationPrint(Attendee $attendee) {
    $data['attendee']           = $attendee;
    $data['recipientsById']     = $attendee->recipients()->orderBy('id')->with('Awards');
    $data['organizations']      = $attendee->recipients()->organizations();

    $pdf = PDF::loadView('documents.recipientsByMinistry');
    return $pdf->download('recipients-by-ministry.pdf');
  }

  /**
  * Return options for an attendee
  *
  * @param Integer $milestone
  * @return \Illuminate\Http\Response
  */

  public function getAccommodations(Attendee $attendee)
  {
    return Accommodation::where('attendee_id', '=', $attendee->id);
  }


}
