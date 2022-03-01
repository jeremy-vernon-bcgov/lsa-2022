<?php

namespace App\Http\Controllers;

use App\Models\Recipient;
use App\Models\Address;
use App\Models\Award;
use App\Models\HistoricalRecipient;
use Illuminate\Http\Request;

class RecipientController extends Controller
{
  /**
  * return a listing of Recipients.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    return Recipient::with(['personalAddress', 'supervisorAddress', 'officeAddress', 'award'])->get();
  }

  /**
  * Show the form for creating a new Recipient.
  *
  * @return \Illuminate\Http\Response
  */
  public function create(Request $request)
  {
  }

  /**
  * Store a new recipient (requires the full record).
  * Fragmentary / Step-wise registration uses different methods (see below).
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $data = array(
      $request->input('guid'),
      $request->input('idir'),
      $request->input('employee_number'),
      $request->input('organization_id')
    );
    return Recipient::insert('insert into recipients (guid, idir, employee_number, organization_id) values (?, ?)', $data);
  }

  /**
  * Retrieve the full record for a recipient.
  *
  * @param  \App\Models\Recipient  $recipient
  * @return \Illuminate\Http\Response
  */
  public function show(string $guid)
  {
    return Recipient::where('guid', $guid)->with([
      'personalAddress',
      'supervisorAddress',
      'officeAddress',
      'award'
      ])->firstOrFail();
  }

  /**
  * Show the form for editing the recipient
  *
  * @param  \App\Models\Recipient  $recipient
  * @return \Illuminate\Http\Response
  */
  public function edit(Recipient $recipient)
  {
    //
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
    //
  }

  /**
  * Remove the recipient from storage.
  *
  * @param  \App\Models\Recipient  $recipient
  * @return \Illuminate\Http\Response
  */
  public function destroy(Recipient $recipient)
  {
    //
  }

  /**
  * Retrieve the recipient from the archived data
  *
  * @param String $email
  * @return \Illuminate\Http\Response
  */
  public function showArchivedRecipientByEmail (string $email)
  {
    return HistoricalRecipient::where('email', $email)->firstOrFail();
  }

  /**
  * Store User employee identification
  *
  * @param \Illuminate\Http\Request $request
  * @return \Illuminate\Http\Response
  */
  public function storeIdentification(Request $request) {
    $recipient = Recipient::where('guid', $request->input('guid'))->first();
    if (!empty($recipient)) {
      // Recipient GUID exists: update recipient identification
      $recipient->government_email = $request->input('government_email');
      $recipient->employee_number = $request->input('employee_number');
      $recipient->full_name = $request->input('full_name');
      $recipient->organization_id = $request->input('organization_id');
      $recipient->branch_name = $request->input('branch_name');
      $recipient->save();
      return $recipient;
    } else {
      $recipient = Recipient::create([
        'guid' => $request->input('guid'),
        'idir' => $request->input('idir'),
        'government_email' => $request->input('government_email'),
        'employee_number' => $request->input('employee_number'),
        'full_name' => $request->input('full_name'),
        'organization_id' => $request->input('organization_id'),
        'branch_name' => $request->input('branch_name')
      ]);
      $recipient->save();
      return $recipient;
    }
  }

  /**
  * Store Milestone-related information
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Models\Recipient $recipient
  * @return \Illuminate\Http\Response
  */
  public function storeMilestone(Request $request, Recipient $recipient) {
    $recipient->milestones = $request->input('milestones');
    $recipient->qualifying_year = $request->input('qualifying_year');
    $recipient->is_bcgeu_member = $request->input('is_bcgeu_member');
    $recipient->retiring_this_year = $request->input('retiring_this_year');
    $recipient->save();
    return $this->getFullRecipient($recipient);
  }

  /**
  * Store Retirement-related information
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @return \Illuminate\Http\Response
  *
  */
  public function storeRetirement(Request $request, Recipient $recipient) {
    // Need to allow recipient to select retirement for this year, but not set a
    // retirement date until ready to submit the registration
    if ($recipient->retiring_this_year && !(empty($request->input('retirement_date')))) {
      $recipient->retirement_date = $request->input('retirement_date');
      $recipient->save();
    }
    return $this->getFullRecipient($recipient);
  }

  /**
  * Store Award selection [TODO: award selection is incomplete]
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @return \Illuminate\Http\Response
  */
  public function storeAward(Request $request, Recipient $recipient) {
    $award = Award::find($request->input('award_id'));
    if (!empty($award)) {
      $recipient->award()->syncWithoutDetaching($award->id, [
        'options' => $request->input('options'),
        'status' => $request->input('status')
      ]);
    }

    return $this->getFullRecipient($recipient);

  }


  /**
  * Store Service Pin related info
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @return \Illuminate\Http\Response
  */
  public function storeServicePins(Request $request, Recipient $recipient) {
    // save non-address data
    $recipient->supervisor_email = $request->input('supervisor_email');

    // check for existing address record
    $addressId = $request->input('supervisor_address_id');
    $supervisorAddress = Address::find($addressId);

    if ($supervisorAddress === null) {
      $supervisorAddress = new Address([
        'prefix' => $request->input('supervisor_address_prefix'),
        'street_address' => $request->input('supervisor_address_street_address'),
        'postal_code' => $request->input('supervisor_address_postal_code'),
        'community' => $request->input('supervisor_address_community')
      ]);
      $supervisorAddress->save();
      $recipient->supervisorAddress()->associate($supervisorAddress);

    } else {
      // update existing address record
      $supervisorAddress->prefix = $request->input('supervisor_address_prefix');
      $supervisorAddress->street_address = $request->input('supervisor_address_street_address');
      $supervisorAddress->postal_code = $request->input('supervisor_address_postal_code');
      $supervisorAddress->community = $request->input('supervisor_address_community');
      $supervisorAddress->save();
    }

    $recipient->save();

    return $this->getFullRecipient($recipient);
  }

  /**
  * Store Declarations
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @return \Illuminate\Http\Response
  */
  public function storeDeclarations(Request $request, Recipient $recipient) {
    $recipient->is_declared = $request->is_declared;
    $recipient->survey_participation = $request->survey_participation;
    $recipient->ceremony_opt_out = $request->ceremony_opt_out;

    $recipient->save();
    return $this->getFullRecipient($recipient);
  }

  /**
  * Store Personal Contact Information
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @return \Illuminate\Http\Response
  */
  public function storePersonalContact(Request $request, Recipient $recipient) {
    // save non-address data
    $recipient->personal_email = $request->input('personal_email');
    $recipient->personal_phone_number = $request->input('personal_phone_number');

    // check for existing address record
    $addressId = $request->input('personal_address_id');
    $personalAddress = Address::find($addressId);

    if ($personalAddress === null) {
      $personalAddress = new Address([
        'prefix' => $request->input('personal_address_prefix'),
        'street_address' => $request->input('personal_address_street_address'),
        'postal_code' => $request->input('personal_address_postal_code'),
        'community' => $request->input('personal_address_community')
      ]);
      $personalAddress->save();
      $recipient->personalAddress()->associate($personalAddress);

    } else {
      // update existing address record
      $personalAddress->prefix = $request->input('personal_address_prefix');
      $personalAddress->street_address = $request->input('personal_address_street_address');
      $personalAddress->postal_code = $request->input('personal_address_postal_code');
      $personalAddress->community = $request->input('personal_address_community');
      $personalAddress->save();
    }

    $recipient->save();

    return $this->getFullRecipient($recipient);

  }

  private function getFullRecipient(Recipient $recipient) {
    return Recipient::where('id', $recipient->id)->with([
      'personalAddress',
      'supervisorAddress',
      'officeAddress',
      'award'
      ])->firstOrFail();
    }
  }
