<?php

namespace App\Http\Controllers;

use App\Mail\RecipientNoCeremonyRegistrationConfirm;
use App\Mail\RecipientRegistrationConfirm;
use App\Mail\SupervisorRegistrationConfirm;
use App\Models\Recipient;
use App\Models\Address;
use App\Models\Award;
use App\Models\HistoricalRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
  * Clear recipient data
  *
  * @param  \App\Models\Recipient  $recipient
  * @return \Illuminate\Http\Response
  */
  public function reset(Recipient $recipient)
  {
    // $recipient->deleted_at = now();
    // $recipient->is_declared = 0;
    // $recipient->milestones = '';
    // $recipient->qualifying_year = NULL;

    // detach awards
    $recipient->award()->detach();
    $recipient->delete();
    return $recipient;
  }

  /**
  * Retrieve the recipient from the archived data
  *
  * @param String $email
  * @return \Illuminate\Http\Response
  */
  public function showArchivedRecipientByEmployeeId (string $employee_number)
  {
    return HistoricalRecipient::where('employee_number', $employee_number)->firstOrFail();
  }

  /**
  * Store User employee identification
  *
  * @param \Illuminate\Http\Request $request
  * @return \Illuminate\Http\Response
  */
  public function storeIdentification(Request $request) {

    //  look up recipient
    $recipient = Recipient::where('guid', $request->input('guid'))->first();

    if (!empty($recipient)) {
      // Recipient GUID exists: update recipient identification details
      $recipient->employee_number = $request->input('employee_number');
      $recipient->first_name = $request->input('first_name');
      $recipient->last_name = $request->input('last_name');
      $recipient->government_email = $request->input('government_email');
      $recipient->personal_phone_number = $request->input('personal_phone_number');
      $recipient->organization_id = $request->input('organization_id');
      $recipient->branch_name = $request->input('branch_name');
    } else {
      $recipient = Recipient::create([
        'guid' => $request->input('guid'),
        'idir' => $request->input('idir'),
        'employee_number' => $request->input('employee_number'),
        'first_name' => $request->input('first_name'),
        'last_name' => $request->input('last_name'),
        'government_email' => $request->input('government_email'),
        'personal_phone_number' => $request->input('personal_phone_number'),
        'organization_id' => $request->input('organization_id'),
        'branch_name' => $request->input('branch_name')
      ]);

    }

    // check for attached office address record
    $addressId = $request->input('office_address_id');
    $officeAddress = Address::find($addressId);

    if ($officeAddress === null) {
      $officeAddress = new Address([
        'prefix' => $request->input('office_address_prefix'),
        'street_address' => $request->input('office_address_street_address'),
        'postal_code' => $request->input('office_address_postal_code'),
        'community' => $request->input('office_address_community')
      ]);
      $officeAddress->save();
      $recipient->officeAddress()->associate($officeAddress);

    } else {
      // update existing address record
      $officeAddress->prefix = $request->input('office_address_prefix');
      $officeAddress->street_address = $request->input('office_address_street_address');
      $officeAddress->postal_code = $request->input('office_address_postal_code');
      $officeAddress->community = $request->input('office_address_community');
      $officeAddress->save();
    }

    $recipient->save();
    return $this->getFullRecipient($recipient);
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

    // update retirement information
    $recipient->retiring_this_year = $request->input('retiring_this_year');
    if ($recipient->retiring_this_year && !(empty($request->input('retirement_date')))) {
      $recipient->retirement_date = $request->input('retirement_date');
    } else {
      $recipient->retirement_date = NULL;
    }

    $recipient->save();
    return $this->getFullRecipient($recipient);
  }

  /**
  * Store Award selection
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @return \Illuminate\Http\Response
  */
  public function storeAward(Request $request, Recipient $recipient) {
    // check if existing award record exists for:
    // - status: 'self-registration'
    // - qualifying_year: matches
    $recipient->award()
      ->wherePivot('status', '=', $request->input('status'))
      ->wherePivot('qualifying_year', '=', $request->input('qualifying_year'))
      ->detach();

    $award = Award::find($request->input('award_id'));
    if (!empty($award)) {
      $recipient->award()->syncWithoutDetaching([$award->id => [
        'qualifying_year' => $request->input('qualifying_year'),
        'options' => $request->input('options'),
        'status' => $request->input('status')
      ]]);
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
    $recipient->supervisor_first_name = $request->input('supervisor_first_name');
    $recipient->supervisor_last_name = $request->input('supervisor_last_name');

    // check for existing address record
    $addressId = $request->input('supervisor_address_id');
    $supervisorAddress = Address::find($addressId);

    if ($supervisorAddress === null) {
      $supervisorAddress = new Address([
        'prefix' => $request->input('supervisor_address_prefix'),
        'pobox' => $request->input('supervisor_address_pobox'),
        'street_address' => $request->input('supervisor_address_street_address'),
        'postal_code' => $request->input('supervisor_address_postal_code'),
        'community' => $request->input('supervisor_address_community')
      ]);
      $supervisorAddress->save();
      $recipient->supervisorAddress()->associate($supervisorAddress);

    } else {
      // update existing address record
      $supervisorAddress->prefix = $request->input('supervisor_address_prefix');
      $supervisorAddress->pobox = $request->input('supervisor_address_pobox');
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

  /**
  * Send confirmation emails for ceremony sign-up
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @return \Illuminate\Http\Response
  */
  private function sendConfirmationEmails(Recipient $recipient) {
      if ($recipient->ceremony_opt_out == true) {
        Mail::to($recipient->government_email)->send(new RecipientNoCeremonyRegistrationConfirm($recipient));
      } else {
        Mail::to($recipient->government_email)->send(new RecipientRegistrationConfirm($recipient));
      }
        Mail::to($recipient->supervisor_email)->send(new SupervisorRegistrationConfirm($recipient));
  }


  /**
  * Get recipient full data by ID
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @return \Illuminate\Http\Response
  */
  private function getFullRecipient(Recipient $recipient) {
    return Recipient::where('id', $recipient->id)->with([
      'personalAddress',
      'supervisorAddress',
      'officeAddress',
      'award'
      ])->firstOrFail();
    }
  }
