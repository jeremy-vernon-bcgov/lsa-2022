<?php

namespace App\Http\Controllers;

use App\Models\Recipient;
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
        return Recipient::all();
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
        return Recipient::where('guid', $guid)->firstOrFail();
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
        $recipient = Recipient::where('guid', $guid)->first();
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

    }

    /**
     * Store Award selection
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Recipient $recipient
     * @return \Illuminate\Http\Response
     */
    public function storeAward(Request $request, Recipient $recipient) {

    }


    /**
     * Store Service Pin related info
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Recipient $recipient
     * @return \Illuminate\Http\Response
     */
    public function storeServicePins(Request $request, Recipient $recipient) {

    }

    /**
     * Store Declarations
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Recipient $recipient
     * @return \Illuminate\Http\Response
     */
    public function storeDeclarations(Request $request, Recipient $recipient) {

    }

    /**
     * Store Personal Contact
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Recipient $recipient
     * @return \Illuminate\Http\Response
     */
    public function storePersonalContact(Request $request,Recipient $recipient) {

    }

    /**
     * Update Confirmation
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Recipient $recipient
     * @return \Illuminate\Http\Response
     */
    public function updateConfirmation(Request $request, Recipient $recipient) {

    }

    /**
     * @param \Illuminate\HttpRequest $request
     * @param String $guid
     * @return \App\Models\Recipient
     */



}
