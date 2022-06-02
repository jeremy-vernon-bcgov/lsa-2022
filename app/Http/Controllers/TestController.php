<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\User;
use App\Models\Recipient;
use App\Models\Ceremony;
use App\Models\Attendee;
use Illuminate\Http\Request;
use App\Classes\StatusHelper;
use App\Classes\MailHelper;
use App\Classes\AttendeesHelper;
use Illuminate\Support\Str;
use DateTime;
use DateInterval;
use DateTimeZone;

use Illuminate\Support\Facades\Log;

class TestController extends Controller
{

  /**
   * Send test registration reminder
   *
   * @return \Illuminate\Http\Response
   */
  public function sendTestReminder(Request $request)
  {
    $this->authorize('assign', Recipient::class);

    $email = $request->input('email');

    // create mail helper utility instance
    $mailer = new MailHelper();

    $attendees = $request->input('attendees');
    foreach ($attendees as $attendee) {
      $recipient = Recipient::find($attendee['id']);
      $mailer->sendRegistrationReminder($recipient, $email);
    }
    return $attendees;

  }

  /**
   * Send test invitation
   *
   * @return \Illuminate\Http\Response
   */
  public function sendTestInvitation(Request $request)
  {

    $this->authorize('assign', Recipient::class);

    // get requested data
    $email = $request->input('email');
    $status = $request->input('status');
    $ceremony = $request->input('ceremony');
    $attendees = $request->input('attendees');

    // create attendees utitlity helper
    $attendeeHelper = new AttendeesHelper();

    foreach ($attendees as $attendeeData) {

      $recipient = Recipient::find($attendeeData['id']);

      // check for ceremony opt-out and registration declaration
      if ($recipient->ceremony_opt_out || !$recipient->is_declared) {
        return response()->json([
          'errors' => "Recipient cannot be assigned a ceremony",
        ], 500);
      }

Log::info('Recipient', array('recipient' => $recipient));
      // get assigned ceremony (must be unique)
      $assignedAttendee = $attendeeHelper->getAssignedAttendee($recipient);
      Log::info('Assigned Attendee', array('attendee' => $assignedAttendee));
      $attendee = Attendee::find($assignedAttendee['id']);
      $attendee->status = 'invited';
      $ceremony = Ceremony::with('locationAddress')->find($assignedAttendee['ceremonies_id']);

      // generate and store RSVP access token
      // - set expiration in two weeks (2 x 604800 seconds)
      $token = Str::random(60);
      // set expiry date for +14 days
      $expiry = new DateTime();
      $expiry->setTimezone(new DateTimeZone('America/Vancouver'));
      $expiry->add(new DateInterval('P14D'));
      // Cache::put($attendee->id, $token, $expiry);

      // send test invitation email
      $mailer = new MailHelper();
      $mailer->sendInvitation($recipient, $ceremony, $attendee, $token, $expiry, $email);
    }

  }

  /**
   * Send test RSVP confirmation
   *
   * @return \Illuminate\Http\Response
   */
  public function sendTestRSVPConfirmation(Request $request)
  {

    $this->authorize('assign', Recipient::class);

    // get requested data
    $email = $request->input('email');
    $status = $request->input('status');
    $ceremony = $request->input('ceremony');
    $attendees = $request->input('attendees');

    // create attendees utitlity helper
    $attendeeHelper = new AttendeesHelper();

    foreach ($attendees as $attendeeData) {
      $recipient = Recipient::find($attendeeData['id']);

      // check for ceremony opt-out and registration declaration
      if ($recipient->ceremony_opt_out || !$recipient->is_declared) {
        return response()->json([
          'errors' => "Recipient cannot be assigned a ceremony",
        ], 500);
      }

      // get assigned ceremony (must be unique)
      $assignedAttendee = $attendeeHelper->getAttendingAttendee($recipient);
      $attendee = Attendee::find($assignedAttendee['id']);
      $attendee->status = 'attending';

      // send test RSVP confirmation email
      $mailer = new MailHelper();
      $mailer->sendRSVPConfirmation($recipient, $attendee, $email);
    }

  }

}
