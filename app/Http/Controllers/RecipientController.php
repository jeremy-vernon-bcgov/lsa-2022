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
    public function create()
    {
        //
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
        //
    }

    /**
     * Retrieve the full record for a recipient.
     *
     * @param  \App\Models\Recipient  $recipient
     * @return \Illuminate\Http\Response
     */
    public function show(string $guid)
    {
        return Recipient::where('guid', $guid)->with(['personalAddress', 'supervisorAddress', 'officeAddress', 'award'])->firstOrFail();
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
        $recipient = Recipient::where('guid', $request->guid)->first();
        if (!empty($recipient)) {
            //GUID is not unique - do we report an error or update the data?
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
        $recipient->milestones = $request->milestone;
        $recipient->qualifying_year = $request->milestone;
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
        $recipient->retiring_this_year = $request->retiring_this_year;
        if ($recipient->retiring_this_year && !(empty($request->retirement_date))) {
            $recipient->retirement_date = $request->retiring_this_year;
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
        $award = Award::find($request->award_id);
        if (!empty($award)) {
            $recipient->awards()->attach($award->id, ['options' => $request->options]);
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
        $supervisorAddress = new Address([
            'prefix' => $request->supervisor_address_prefix,
            'street_address' => $request->supervisor_address_street_address,
            'postal_code'   => $request->supervisor_address_postal_code,
            'community' => $request->supervisor_address_postal_code
            ]);
        $recipient->supervisor_full_name = $request->supervisor_full_name;
        $recipient->supervisor_email = $request->supervisor_email;
        $recipient->supervisorAddress()->save($supervisorAddress);
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
     * Store Personal Contact
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Recipient $recipient
     * @return \Illuminate\Http\Response
     */
    public function storePersonalContact(Request $request,Recipient $recipient) {
        $personalAddress = new Address([
           'prefix' => $request->personal_address_prefix,
           'street_address' => $request->personal_address_street_address,
           'postal_code' => $request->personal_address_postal_code,
           'community' => $request->personal_address_community
        ]);
        $recipient->personal_email = $request->personal_email;
        $recipient->personal_phone_number = $request->personal_phone_number;
        $recipient->personalAddress()->save($personalAddress);
        $recipient->save();

        return $this->getFullRecipient($recipient);

    }

    private function getFullRecipient(Recipient $recipient) {
        return Recipient::where('id', $recipient->id)->with(['personalAddress', 'supervisorAddress', 'officeAddress', 'award'])->firstOrFail();
    }
}
