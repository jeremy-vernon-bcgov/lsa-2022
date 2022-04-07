<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendee;
use App\Models\Recipient;
use App\Models\Award;
use App\Models\Organization;
use App\Models\Ceremony;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
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
  * Create new attendee.
  *
  * @param Attendee $attendee
  */

  public function assign (Request $request, Recipient $recipient) {
    $this->authorize('update', Attendee::class);

    Log::info('Assign Recipient', array('context' => $recipient));
    $attendee = Attendee::find($recipient->id);

    // check if existing award record exists for:
    // - status: 'self-registration'
    // - qualifying_year: matches
    $recipient->ceremony()
    ->wherePivot('status', '=', $request->input('award.status'))
    ->wherePivot('qualifying_year', '=', $request->input('qualifying_year'))
    ->detach();

    $ceremony = Award::find($request->input('award.id'));
    if (!empty($award)) {
      $recipient->awards()->syncWithoutDetaching([$award->id => [
        'attendable_id' => $request->input('attendable_id'),
        'attendable_type' => $request->input('attendable_type'),
        'status' => $request->input('status')
        ]]);
      }

      return $this->getFullRecipient($recipient);

    if (empty($attendee)) {
      // create new attendee
      $attendee = Attendee::create([
        'attendable_id' => $request->input('attendable_id'),
        'attendable_type' => $request->input('attendable_type'),
        'status' => $request->input('status')
      ]);
    } else {
      // update attendee date time
      $attendee->attendable_id = $request->input('attendable_id');
      $attendee->attendable_type = $request->input('attendable_type');
      $attendee->status = $request->input('status');
    }

    $attendee->save();
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
