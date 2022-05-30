<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendee;
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
  * Displays a list of all ceremonies
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
    return Attendee::where('attendees.id', $attendee->id)->with([
      'ceremonies',
      'accommodations'
    ])
    ->firstOrFail();
  }

  /**
  * Retrieve attendees of requested ceremony.
  *
  * @param Ceremony $ceremony
  */

  public function getByCeremony (Ceremony $ceremony) {
    $this->authorize('view', Ceremony::class);

    $attendees = Attendee::where('ceremonies_id', $ceremony->id)
    ->with(['ceremonies', 'accommodations']);

    $recipients = clone $attendees;
    $guests = clone $attendees;
    $guest_count = clone $attendees;
    $recipient_count = $attendees->where('attendable_type', '=', 'App\Models\Recipient')->count();
    $guest_count = $guest_count->where('attendable_type', '=', 'App\Models\Guest')->count();

    return array(
      'recipients' => $recipients->recipients()->get(),
      'guests' => $guests->guests()->get(),
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

    // attendee must be attending ceremony to edit attendee details
    if (!$attendee->status === 'attending') {
      return response()->json(['error' => 'Invalid'], 422);
    }

    $attendeeHelper = new AttendeesHelper();

    // recipient accepted the invitation
    $attendeeHelper->setAccommodations($attendee, $request->input('recipient_options'));
    $attendee->save();

    // add guest data (if exists)
    if ($request->input('guest')) {
      $attendeeHelper->addGuest($attendee, $request->input('guest_options'), $attendee->ceremonies_id);
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

      if ($hasGuest) {
        // handle added guest and options
        $attendeeHelper->addGuest($recipient, $attendee, $request->input('data.guest_options'));
      }
      else {
        // remove guest
        $attendeeHelper->removeGuest($recipient);
        $recipient->guest()->dissociate();
      }

      // handle retirement
      Log::info('Retirement', array('is_retiring' => $isRetiring, 'date' => $request->input('data.retirement_date'), 'recipient' => $recipient));

      if ($isRetiring) {
        // update forwarding address
        $addressHelper->attachRecipient($recipient, $request->input('data.contact'));
        // set the retirement date
        $recipient->retirement_date = $request->input('data.retirement_date');
        $recipient->save();
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
  * Displays a list of all recipients for a attendee with accommodations
  *
  * @param Attendee $attendee
  */

  public function accommodations (Attendee $attendee) {

  }

  /**
  * Displays a list of all recipients for a attendee
  *
  * @param Attendee $attendee
  */

  public function recipients (Attendee $attendee) {

  }


  /**
  * Gets all the guest information for a attendee night.
  *
  * @param Attendee $attendee
  */

  public function guests (Attendee $attendee) {

  }

  /**
  * Generates PDF for printed list of all recipients for a attendee
  * for use in attendee-prep binder.
  *
  * @param Attendee $attendee
  */
  public function allRecipientsPrint (Attendee $attendee) {

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
  * Generates PDF for printing name-tags.
  *
  * @param Attendee $attendee
  */

  public function nameTagsPrint(Attendee $attendee) {

  }

  /**
  * Generates the PDF for the printed program.
  *
  * @param Attendee $attendee
  */

  public function programPrint(Attendee $attendee) {

  }

  /**
  * Generates PDF for printing place cards for a attendee.
  *
  * @param Attendee $attendee
  */

  public function placeCardsPrint(Attendee $attendee) {

  }

  /**
  * Generates PDF for printing the executive badges for a attendee.
  *
  * @param Attendee $attendee
  */

  public function executiveBadgesPrint(Attendee $attendee) {

  }



}
