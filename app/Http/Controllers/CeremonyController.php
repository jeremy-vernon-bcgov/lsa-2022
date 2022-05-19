<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ceremony;
use App\Models\Recipient;
use App\Models\Award;
use App\Models\Organization;
use App\Classes\AddressHelper;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class CeremonyController extends Controller
{

  /**
  * Displays a list of all ceremonies
  *
  * @param Ceremony $ceremony
  */

  public function index () {
    $this->authorize('viewAny', Ceremony::class);
    return Ceremony::attendance()->get();
  }

  /**
  * Retrieve full record of ceremony.
  *
  * @param Ceremony $ceremony
  */

  public function show (Ceremony $ceremony) {
    $this->authorize('view', Ceremony::class);
    return $ceremony;
  }

  /**
  * Create new ceremony.
  *
  * @param Ceremony $ceremony
  */

  public function store (Request $request) {
    $this->authorize('create', Ceremony::class);

    // create new ceremony
    $ceremony = Ceremony::create([
      'scheduled_datetime' => $request->input('scheduled_datetime'),
      'location_name' => $request->input('location_name'),
    ]);

    // update ceremony location address info
    $addressHelper = new AddressHelper();
    $addressHelper->attachCeremony($ceremony, $request->input('location_address'));

    $ceremony->save();
    return $ceremony;
  }

  /**
  * Update record of ceremony.
  *
  * @param Ceremony $ceremony
  */

  public function update (Request $request, Ceremony $ceremony) {
    $this->authorize('update', Ceremony::class);

    // update ceremony data
    $ceremony->scheduled_datetime = $request->input('scheduled_datetime');
    // $ceremony->location_name = $request->input('location_name');

    // update ceremony location address info
    // $addressHelper = new AddressHelper();
    // $addressHelper->attachCeremony($ceremony, $request->input('location_address'));

    $ceremony->save();
    return $ceremony;
  }

  /**
  * Delete record of ceremony.
  *
  * @param Ceremony $ceremony
  */

  public function destroy (Request $request, Ceremony $ceremony) {
    $this->authorize('delete', Ceremony::class);
    $ceremony->delete();
    return $ceremony;
  }


  /**
  * Displays a list of all recipients for a ceremony with accommodations
  *
  * @param Ceremony $ceremony
  */

  public function accommodations (Ceremony $ceremony) {

  }

  /**
  * Displays a list of all recipients for a ceremony
  *
  * @param Ceremony $ceremony
  */

  public function recipients (Ceremony $ceremony) {

  }


  /**
  * Gets all the guest information for a ceremony night.
  *
  * @param Ceremony $ceremony
  */

  public function guests (Ceremony $ceremony) {

  }

  /**
  * Generates PDF for printed list of all recipients for a ceremony
  * for use in ceremony-prep binder.
  *
  * @param Ceremony $ceremony
  */
  public function allRecipientsPrint (Ceremony $ceremony) {

  }

  /**
  * Generates a PDF for printing the list of recipients by ID;
  *
  * @param Ceremony $ceremony
  * @return \Illuminate\Http\Response
  */

  public function awardsPresentationPrint(Ceremony $ceremony) {
    $data['ceremony']           = $ceremony;
    $data['recipientsById']     = $ceremony->recipients()->orderBy('id')->with('Awards');
    $data['organizations']      = $ceremony->recipients()->organizations();

    $pdf = PDF::loadView('documents.recipientsByMinistry');
    return $pdf->download('recipients-by-ministry.pdf');
  }

  /**
  * Generates PDF for printing name-tags.
  *
  * @param Ceremony $ceremony
  */

  public function nameTagsPrint(Ceremony $ceremony) {

  }

  /**
  * Generates the PDF for the printed program.
  *
  * @param Ceremony $ceremony
  */

  public function programPrint(Ceremony $ceremony) {

  }

  /**
  * Generates PDF for printing place cards for a ceremony.
  *
  * @param Ceremony $ceremony
  */

  public function placeCardsPrint(Ceremony $ceremony) {

  }

  /**
  * Generates PDF for printing the executive badges for a ceremony.
  *
  * @param Ceremony $ceremony
  */

  public function executiveBadgesPrint(Ceremony $ceremony) {

  }



}
