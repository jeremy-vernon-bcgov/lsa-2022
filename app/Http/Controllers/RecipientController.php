<?php

namespace App\Http\Controllers;

use App\Models\Recipient;
use App\Models\Address;
use App\Models\Award;
use App\Models\Attendee;
use App\Models\Ceremony;
use App\Models\User;
use App\Models\HistoricalRecipient;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Classes\RecipientsHelper;
use App\Classes\AttendeesHelper;
use App\Classes\StatusHelper;
use App\Classes\MailHelper;

use DateTime;

class RecipientController extends Controller
{

  /**
  * Return a filtered list of Recipients.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(User $user, Request $request)
  {

    $this->authorize('viewAny', Recipient::class);

    // get authenticated user
    $authUser = auth()->user();

    // refresh status
    $attendeeHelper = new AttendeesHelper();
    $attendeeHelper->refresh();

    // get pagination
    $resultsPerPage = $request->query('results_per_page', 25);

    // get recipients
    return Recipient::with([
      'personalAddress',
      'supervisorAddress',
      'officeAddress',
      'attendee',
      'awards'])
      ->declared($authUser)
      ->userOrgs($authUser)
      ->historical()
      ->filter($request)
      ->paginate($resultsPerPage);
    }

    /**
    * Retrieve the full record for a recipient.
    *
    * @param  \App\Models\Recipient  $recipient
    * @return \Illuminate\Http\Response
    */
    public function show(Recipient $recipient)
    {
      // authorize view
      $this->authorize('view', $recipient);
      $recipientHelper = new RecipientsHelper();
      return $recipientHelper->get($recipient->id);
    }

    /**
    * Delete recipient.
    *
    * @param  \App\Models\Recipient  $recipient
    * @return \Illuminate\Http\Response
    */
    public function destroy(Recipient $recipient)
    {
      // authorize deletion
      $this->authorize('delete', $recipient);
      // delete recipient record
      $recipient->awards()->detach();
      $recipient->delete();
      return $recipient;
    }

    /**
    * Store a new recipient (delegated registration).
    * Self-registration uses different methods (see below).
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {

      $this->authorize('create', Recipient::class);

      $recipientHelper = new RecipientsHelper();

      // create new recipient record
      $recipient = $recipientHelper->create($request);

      // update recipient data sections
      $recipient = $recipientHelper->storeMilestone($request, $recipient);
      $recipient = $recipientHelper->storeAward($request, $recipient);
      $recipient = $recipientHelper->storePersonalContact($request, $recipient);
      $recipient = $recipientHelper->storeServicePins($request, $recipient);
      $recipient = $recipientHelper->storeDeclarations($request, $recipient);
      $recipient = $recipientHelper->storeAdmin($request, $recipient);
      $recipient->save();

      return $this->get($recipient->id);
    }

    /**
    * Update the recipient - requires the full resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Recipient  $recipient
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Recipient $recipient)
    {
      $this->authorize('update', $recipient);

      $recipientHelper = new RecipientsHelper();

      $recipient = $recipientHelper->update($request, $recipient);

      // update recipient data sections
      $recipient = $recipientHelper->storeMilestone($request, $recipient);
      $recipient = $recipientHelper->storeAward($request, $recipient);
      $recipient = $recipientHelper->storePersonalContact($request, $recipient);
      $recipient = $recipientHelper->storeServicePins($request, $recipient);
      $recipient = $recipientHelper->storeDeclarations($request, $recipient, false);
      $recipient = $recipientHelper->storeAdmin($request, $recipient);
      $recipient->save();

      return $recipientHelper->get($recipient->id);
    }

    /**
    * Assign ceremony status to recipient
    *
    * If the recipient has a status other than "assigned" or "waitlisted"
    * (e.g. RSVP-yes, invited) for any other ceremony night, the system
    * will not change their record. If they have a status of “assigned”
    * for another ceremony, it will be overwritten. They will then have
    * an association with a ceremony night marked with a status of “assigned”
    *
    * @param \Illuminate\Http\Request $request
    * @param \App\Model\Recipient $recipient
    * @return \Illuminate\Http\Response
    */
    public function assign(Request $request) {

      $this->authorize('assign', Recipient::class);

      // get requested status, ceremony update
      $status = $request->input('status');
      $ceremony = $request->input('ceremony');
      $attendees = $request->input('attendees');

      // create attendees utitlity helper
      $attendeeHelper = new AttendeesHelper();

      foreach ($attendees as $attendee) {
        $recipient = Recipient::find($attendee['id']);

        // check for ceremony opt-out and registration declaration
        if ($recipient->ceremony_opt_out || !$recipient->is_declared) {
          return response()->json([
            'errors' => "Recipient cannot be assigned a ceremony",
          ], 500);
        }

        // check if retirement date has lapsed
        // - update recipient preferred email to personal
        $retirement_date = new DateTime($recipient->retirement_date);
        $now = new DateTime();
        if (!empty($recipient->retirement_date) && $retirement_date < $now) {
          $recipient->preferred_email = 'personal';
          $recipient->save();
        }

        $attendees = $attendeeHelper->assignStatus($recipient, $status, (int)$ceremony);
      }

      return $attendees;
    }

    /**
    * Send reminder notification for registrations (authorized)
    *
    * @param \Illuminate\Http\Request $request
    * @param \App\Model\Recipient $recipient
    * @return \Illuminate\Http\Response
    */
    public function remind(Request $request) {
      $this->authorize('assign', Recipient::class);
      // create mail helper utility instance
      $mailer = new MailHelper();

      $attendees = $request->input('attendees');
      foreach ($attendees as $attendee) {
        $recipient = Recipient::find($attendee['id']);
        $mailer->sendRegistrationReminder($recipient);
      }
      return $attendees;
    }

    /**
    * Send RSVP confirmation to recipient
    *
    * @param \Illuminate\Http\Request $request
    * @param \App\Model\Recipient $recipient
    * @return \Illuminate\Http\Response
    */
    public function confirm(Request $request) {
      $this->authorize('assign', Recipient::class);
      // create mail helper utility instance
      $mailer = new MailHelper();

      $attendees = $request->input('attendees');
      foreach ($attendees as $attendee) {
        $recipient = Recipient::find($attendee['id']);
        $mailer->sendRegistrationReminder($recipient);
      }
      return $attendees;
    }


      /**
       * (Re-)send RSVP confirmation
       *
       * @return \Illuminate\Http\Response
       */
      public function confirmRSVP(Request $request)
      {

        $this->authorize('assign', Recipient::class);

        // get requested data
        $status = $request->input('status');
        $ceremony = $request->input('ceremony');
        $attendees = $request->input('attendees');

        // create attendees utitlity helper
        $attendeeHelper = new AttendeesHelper();

        foreach ($attendees as $attendeeData) {
          $recipient = Recipient::find($attendeeData['id']);
          // get assigned ceremony (must be unique)
          $assignedAttendee = $attendeeHelper->getAttendingAttendee($recipient);
          $attendee = Attendee::find($assignedAttendee['id']);

          // check for ceremony opt-out and registration declaration
          if (empty($attendee) || $recipient->ceremony_opt_out || !$recipient->is_declared) {
            return response()->json([
              'errors' => "Recipient cannot be assigned a ceremony",
            ], 500);
          }

          // send test RSVP confirmation email
          $mailer = new MailHelper();
          $mailer->sendRSVPConfirmation($recipient, $attendee);
        }

      }


  }
