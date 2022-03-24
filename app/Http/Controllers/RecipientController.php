<?php

namespace App\Http\Controllers;

use App\Mail\RecipientNoCeremonyRegistrationConfirm;
use App\Mail\RecipientRegistrationConfirm;
use App\Mail\SupervisorRegistrationConfirm;
use App\Models\Recipient;
use App\Models\Address;
use App\Models\Award;
use App\Models\User;
use App\Models\HistoricalRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RecipientController extends Controller
{
  /**
  * Return a listing of Recipients.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(User $user, Request $request)
  {

    $this->authorize('viewAny', Recipient::class);

    $authUser = auth()->user();

    Log::info('Manage Recipients', array(
      'user' => $user,
      'auth' => auth()->user(),
      'roles' => $user->getRoleNames(),
      'orgs' => $authUser->organizations()->get(),
      'filtered' => $authUser
    ));

    // filter user-associated organizations
    $orgs = [];
    foreach ($authUser->organizations()->get() as $org){
      $orgs[] = $org->id;
    }

    Log::info('Manage Recipients', array(
      'filteredOrgs' => $orgs
    ));

    // get recipients list data
    // - filter deleted recipients
    // - include historical recipient flag
    return count($orgs) === 0
    ? Recipient::with([
      'personalAddress',
      'supervisorAddress',
      'officeAddress',
      'awards'])
      ->whereNull('deleted_at')
      ->leftJoin('historical_recipients', function($join) {
        $join->on('recipients.employee_number','=','historical_recipients.employee_number');
      })
      ->select('recipients.*', 'historical_recipients.id AS historical')
      ->get()
    : Recipient::with([
      'personalAddress',
      'supervisorAddress',
      'officeAddress',
      'awards'])
      ->whereNull('deleted_at')
      ->whereIn('organization_id', $orgs)
      ->leftJoin('historical_recipients', function($join) {
        $join->on('recipients.employee_number','=','historical_recipients.employee_number');
      })
      ->select('recipients.*', 'historical_recipients.id AS historical')
      ->get();
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

      return $this->getFullRecipient($recipient);
    }

    /**
    * Return a listing of Recipients.
    *
    * @return \Illuminate\Http\Response
    */
    public function showDeleted()
    {
      // authorize view
      $this->authorize('viewAny', Recipient::class);

      return Recipient::with([
        'personalAddress',
        'supervisorAddress',
        'officeAddress',
        'awards'])
        ->whereNotNull('deleted_at')
        ->leftJoin('historical_recipients', function($join) {
          $join->on('recipients.employee_number','=','historical_recipients.employee_number');
        })
        ->select('recipients.*', 'historical_recipients.id AS historical')
        ->get();

    }

    /**
    * Delete (Disable) recipient.
    *
    * @param  \App\Models\Recipient  $recipient
    * @return \Illuminate\Http\Response
    */
    public function disable(Recipient $recipient)
    {
      // authorize view
      $this->authorize('viewAny', Recipient::class);
      $recipient->deleted_at = date("Y-m-d H:i:s");
      $recipient->save();

      return $this->getFullRecipient($recipient);
    }


    /**
    * Retrieve the full record for a recipient.
    *
    * @param  \App\Models\Recipient  $recipient
    * @return \Illuminate\Http\Response
    */
    public function showByGUID(string $guid)
    {
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
      * Store a new recipient (requires the full record).
      * Fragmentary / Step-wise registration uses different methods (see below).
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
      public function store(Request $request)
      {

        // create new recipient record
        $recipient = self::createRecipient($request);

        // update recipient data sections
        $recipient = self::storeMilestone($request, $recipient);
        $recipient = self::storeAward($request, $recipient);
        $recipient = self::storePersonalContact($request, $recipient);
        $recipient = self::storeServicePins($request, $recipient);
        $recipient = self::storeDeclarations($request, $recipient);

        $recipient->save();
        return $this->getFullRecipient($recipient);
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
        $recipient = self::updateRecipient($request, $recipient);

        // update recipient data sections
        $recipient = self::storeMilestone($request, $recipient);
        $recipient = self::storeAward($request, $recipient);
        $recipient = self::storePersonalContact($request, $recipient);
        $recipient = self::storeServicePins($request, $recipient);
        $recipient = self::storeDeclarations($request, $recipient);

        $recipient->save();
        return $this->getFullRecipient($recipient);

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
        $recipient->awards()->detach();
        $recipient->delete();
        return $recipient;
      }

      /**
      * Retrieve historical recipient from the archived data
      * - search using Employee Number
      *
      * @param String $email
      * @return \Illuminate\Http\Response
      */
      public function showArchivedRecipientByEmployeeId (string $employee_number)
      {
        return HistoricalRecipient::where('employee_number', $employee_number)->firstOrFail();
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
        ? self::updateRecipient($request, $recipient)
        : self::createRecipient($request);

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
        $recipient->awards()
        ->wherePivot('status', '=', $request->input('award.status'))
        ->wherePivot('qualifying_year', '=', $request->input('qualifying_year'))
        ->detach();

        $award = Award::find($request->input('award.id'));
        if (!empty($award)) {
          $recipient->awards()->syncWithoutDetaching([$award->id => [
            'qualifying_year' => $request->input('qualifying_year'),
            'options' => $request->input('award.options'),
            'status' => $request->input('award.status')
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
          $addressId = $request->input('supervisor_address.id');
          $supervisorAddress = Address::find($addressId);
          $supervisorAddressData = $request->input('supervisor_address');

          if ($supervisorAddress === null) {
            // create supervisor address record
            $supervisorAddress = self::createAddress($supervisorAddressData);
            $recipient->supervisorAddress()->associate($supervisorAddress);
          } else {
            // update supervisor address record
            self::updateAddress($addressId, $supervisorAddressData);
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
          $this->sendConfirmationEmails($recipient);

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
          $addressId = $request->input('personal_address.id');
          $personalAddress = Address::find($addressId);
          $personalAddressData = $request->input('personal_address');

          if ($personalAddress === null) {
            // create personal address record
            $personalAddress = self::createAddress($personalAddressData);
            $recipient->personalAddress()->associate($personalAddress);
          } else {
            // update personal address record
            self::updateAddress($addressId, $personalAddressData);
          }

          $recipient->save();
          return $this->getFullRecipient($recipient);

        }


        /**
        * Create recipient record
        *
        * @param  \App\Models\Recipient  $recipient
        * @return \Illuminate\Http\Response
        */
        public function createRecipient(Request $request)
        {
          // create new recipient
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

          // attach office contact information record
          $officeAddress = self::createAddress($request->input('office_address'));
          $recipient->officeAddress()->associate($officeAddress);

          $recipient->save();
          return $recipient;
        }

        /**
        * Update recipient record identification
        *
        * @param  \App\Models\Recipient  $recipient
        * @return \Illuminate\Http\Response
        */
        public function updateRecipient(Request $request, Recipient $recipient)
        {
          // Recipient GUID exists: update recipient identification details
          $recipient->employee_number = $request->input('employee_number');
          $recipient->first_name = $request->input('first_name');
          $recipient->last_name = $request->input('last_name');
          $recipient->government_email = $request->input('government_email');
          $recipient->personal_phone_number = $request->input('personal_phone_number');
          $recipient->organization_id = $request->input('organization_id');
          $recipient->branch_name = $request->input('branch_name');

          // update office contact information record
          // check for existing address record
          $addressId = $request->input('office_address.id');
          $officeAddress = Address::find($addressId);
          $officeAddressData = $request->input('office_address');

          if ($officeAddress === null) {
            // create office address record
            $officeAddress = self::createAddress($officeAddressData);
            $recipient->officeAddress()->associate($officeAddress);
          } else {
            // update office address record
            self::updateAddress($addressId, $officeAddressData);
          }

          $recipient->save();
          return $recipient;
        }

        /**
        * Create address record
        *
        * @param  \App\Models\Recipient  $recipient
        * @return \Illuminate\Http\Response
        */
        public function createAddress($data)
        {
          $address = new Address($data);
          $address->save();
          return $address;
        }

        /**
        * Update address record
        *
        * @param  $id
        * @param  $data
        * @return \Illuminate\Http\Response
        */
        public function updateAddress($id, $data)
        {
          $address = Address::find($id);

          // update existing address record
          if (!empty($address)){
            $address->prefix = $data['prefix'];
            $address->pobox = array_key_exists("pobox", $data) ? $data['pobox'] : '';
            $address->street_address = $data['street_address'];
            $address->postal_code = $data['postal_code'];
            $address->community = $data['community'];
            $address->save();
          }

          return $address;
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
          return Recipient::where('recipients.id', $recipient->id)->with([
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

          public function generateAllRecipientReport() {
            //return view ('documents.recipientsByMinistry');

            $pdf = PDF::loadView('documents.recipientsByMinistry');
            return $pdf->download('recipients-by-ministry.pdf');

          }

        }
