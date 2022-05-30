<?php
namespace App\Classes;
use App\Models\Recipient;
use App\Models\Address;
use App\Models\Award;
use App\Models\Attendee;
use App\Models\Ceremony;
use App\Models\User;
use App\Classes\StatusHelper;
use App\Classes\AddressHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DateTime;
use DateInterval;
use DateTimeZone;

class RecipientsHelper
{

  /**
  * Assign a recipient to a ceremony
  *
  * @return Array
  */

  public function assign($attendable) {
    return NULL;
  }

  /**
  * Get recipient full data by ID
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @return \Illuminate\Http\Response
  */
  public function get(int $id) {
    return Recipient::where('recipients.id', $id)->with([
      'personalAddress',
      'supervisorAddress',
      'officeAddress',
      'awards',
      'attendee'
    ])
    ->historical()
    ->firstOrFail();
  }

  /**
  * Create recipient record
  *
  * @param  \App\Models\Recipient  $recipient
  * @return \Illuminate\Http\Response
  */
  public function create(Request $request)
  {
    // create new recipient
    $recipient = Recipient::create([
      'guid' => $request->input('guid'),
      'idir' => $request->input('idir'),
      'employee_number' => $request->input('employee_number'),
      'first_name' => $request->input('first_name'),
      'last_name' => $request->input('last_name'),
      'government_email' => $request->input('government_email'),
      'government_phone_number' => $request->input('government_phone_number'),
      'personal_email' => $request->input('personal_email'),
      'preferred_email' => $request->input('preferred_email'),
      'organization_id' => $request->input('organization_id'),
      'branch_name' => $request->input('branch_name')
    ]);

    // attach office address info
    $addressData = $request->input('office_address');
    $addressData['type'] = 'office';
    $addressHelper =  new AddressHelper();
    $addressHelper->attachRecipient($recipient, $addressData);

    $recipient->save();
    return $recipient;
  }


  /**
  * Update recipient record identification
  *
  * @param  \App\Models\Recipient  $recipient
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, Recipient $recipient)
  {
    // update recipient identification details
    $recipient->employee_number = $request->input('employee_number');
    $recipient->first_name = $request->input('first_name');
    $recipient->last_name = $request->input('last_name');
    $recipient->government_email = $request->input('government_email');
    $recipient->government_phone_number = $request->input('government_phone_number');
    $recipient->personal_email = $request->input('personal_email');
    $recipient->organization_id = $request->input('organization_id');
    $recipient->preferred_email = $request->input('preferred_email');
    $recipient->branch_name = $request->input('branch_name');

    // attach office address info
    $addressData = $request->input('office_address');
    $addressData['type'] = 'office';
    $addressHelper =  new AddressHelper();
    $addressHelper->attachRecipient($recipient, $addressData);

    $recipient->save();
    return $recipient;
  }

  /**
  * Get recipient full data by GUID
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @return \Illuminate\Http\Response
  */
  public function getByGUID(Recipient $recipient) {
    return Recipient::where('guid', $guid)->with([
      'personalAddress',
      'supervisorAddress',
      'officeAddress',
      'awards'
    ])
    ->leftJoin('historical_recipients', function($join) {
      $join->on('recipients.employee_number','=','historical_recipients.employee_number');
    })
    ->select('recipients.*', 'historical_recipients.id AS historical')
    ->firstOrFail();
  }

  /**
  * Store recipient employee identification
  *
  * @param \Illuminate\Http\Request $request
  * @return \Illuminate\Http\Response
  */
  public function storeIdentification(Request $request) {

    //  reject missing GUIDs for self-registration
    if (empty($request->input('guid'))) return null;

    //  look up recipient
    $recipient = Recipient::where('guid', $request->input('guid'))->first();

    $recipient = !empty($recipient)
    ? self::update($request, $recipient)
    : self::create($request);

    $recipient->save();

    return self::get($recipient->id);
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
      // check if retirement date has lapsed
      // - set email preference to personal email
      $retirement_date = new DateTime($recipient->retirement_date);
      $now = new DateTime();
      if ($retirement_date < $now) {
        $recipient->preferred_email = 'personal';
      }
    } else {
      $recipient->retirement_date = NULL;
    }

    $recipient->save();
    return self::get($recipient->id);
  }

  /**
  * Store Award selection
  *
  * @param \Illuminate\Http\Request $request
  * @param \App\Model\Recipient $recipient
  * @return \Illuminate\Http\Response
  */
  public function storeAward(Request $request, Recipient $recipient) {
    // check if existing award record exists
    $recipient->awards()->detach();

    $award = Award::find($request->input('award.id'));
    if (!empty($award)) {
      // update award requested
      $recipient->awards()->syncWithoutDetaching([$award->id => [
        'qualifying_year' => $request->input('qualifying_year'),
        'options' => $request->input('award.options'),
        'status' => $request->input('award.status')
        ]]);
      }
      else {
        // remove any award relations
        $recipient->awards()->detach();
      }

      return self::get($recipient->id);
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

      // attach supervisor address info
      $addressData = $request->input('supervisor_address');
      $addressData['type'] = 'supervisor';
      $addressHelper =  new AddressHelper();
      $addressHelper->attachRecipient($recipient, $addressData);

      $recipient->save();
      return self::get($recipient->id);
    }

    /**
    * Store Declarations
    *
    * @param \Illuminate\Http\Request $request
    * @param \App\Model\Recipient $recipient
    * @return \Illuminate\Http\Response
    */
    public function storeDeclarations(Request $request, Recipient $recipient, bool $sendEmail=true) {
      $recipient->is_declared = $request->is_declared;
      $recipient->survey_participation = $request->survey_participation;
      $recipient->ceremony_opt_out = $request->ceremony_opt_out;
      $recipient->save();

      // create mail helper utility instance
      $mailer = new MailHelper();

      // send confirmation email (if requested)
      if ($recipient->is_declared && $sendEmail) $mailer->sendConfirmation($recipient);

      return self::get($recipient->id);
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
      $recipient->personal_phone_number = $request->input('personal_phone_number');

      // attach supervisor address info
      $addressData = $request->input('personal_address');
      $addressData['type'] = 'personal';
      $addressHelper =  new AddressHelper();
      $addressHelper->attachRecipient($recipient, $addressData);

      $recipient->save();
      return self::get($recipient->id);

    }

    /**
    * Store Administrative Notes
    *
    * @param \Illuminate\Http\Request $request
    * @param \App\Model\Recipient $recipient
    * @return \Illuminate\Http\Response
    */
    public function storeAdmin(Request $request, Recipient $recipient) {
      $recipient->admin_notes = $request->admin_notes;
      $recipient->save();
      return self::get($recipient->id);
    }




  }
