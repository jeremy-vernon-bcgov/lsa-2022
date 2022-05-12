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
    Log::info('Attendee', array('context' => $attendee));

    return $attendee;
  }

  /**
  * Retrieve attendees of requested ceremony.
  *
  * @param Ceremony $ceremony
  */

  public function getByCeremony (Ceremony $ceremony) {
    $this->authorize('view', Ceremony::class);

    return Attendee::where('ceremonies_id', $ceremony->id)
    ->with(['ceremonies'])
    ->get();

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
  *
  * @param string $key
  * @param string $token
  */

  public function setRSVP (Request $request, Attendee $attendee, string $token) {

    $attending = $request->input('attending');
    $attendeeHelper = new AttendeesHelper();
    $addressHelper = new AddressHelper();
    $recipient = Recipient::where('id', '=', $attendee->attendable_id)->firstOrFail();

    if ($attending) {
      // recipient accepted the invitation
      $attendeeHelper->setAccommodations($attendee, $request->input('data'));
      $attendee->status = 'attending';
      $attendee->save();
    }
    else {
      // recipient declined the invitation
      // - save forwarding address info
      $addressHelper->attach($recipient, $request->input('data'));
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
