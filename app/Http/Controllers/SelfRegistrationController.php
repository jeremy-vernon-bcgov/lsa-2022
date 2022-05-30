<?php

namespace App\Http\Controllers;

use App\Models\Recipient;
use App\Models\HistoricalRecipient;
use Illuminate\Http\Request;
use App\Classes\RecipientsHelper;
use App\Classes\StatusHelper;

use Illuminate\Support\Facades\Log;

class SelfRegistrationController extends Controller
{

    /**
    * Retrieve the full record for a recipient from GUID.
    *
    * @param  \App\Models\Recipient  $recipient
    * @return \Illuminate\Http\Response
    */
    public function showByGUID(string $guid)
    {
      $recipientHelper = new RecipientsHelper();
      return $recipientHelper->getByGUID($guid);
    }

    /**
    * Store recipient employee identification
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function storeIdentification(Request $request) {

      // check registration system status
      $processor = new StatusHelper();
      if (!$processor->isRegistrationActive()) {
        return response()->json(['error' => 'Unauthorized.'], 401);
      }

      $recipientHelper = new RecipientsHelper();
      $recipientHelper->storeIdentification($request);
    }

    /**
    * Store Milestone-related information
    *
    * @param \Illuminate\Http\Request $request
    * @param \App\Models\Recipient $recipient
    * @return \Illuminate\Http\Response
    */
    public function storeMilestone(Request $request, Recipient $recipient) {
      $recipientHelper = new RecipientsHelper();
      $recipientHelper->storeMilestone($request, $recipient);
    }

    /**
    * Store Award selection
    *
    * @param \Illuminate\Http\Request $request
    * @param \App\Model\Recipient $recipient
    * @return \Illuminate\Http\Response
    */
    public function storeAward(Request $request, Recipient $recipient) {
      $recipientHelper = new RecipientsHelper();
      $recipientHelper->storeMilestone($request, $recipient);
      }


      /**
      * Store Service Pin related info
      *
      * @param \Illuminate\Http\Request $request
      * @param \App\Model\Recipient $recipient
      * @return \Illuminate\Http\Response
      */
      public function storeServicePins(Request $request, Recipient $recipient) {
        $recipientHelper = new RecipientsHelper();
        $recipientHelper->storeServicePins($request, $recipient);
      }

      /**
      * Store Declarations
      *
      * @param \Illuminate\Http\Request $request
      * @param \App\Model\Recipient $recipient
      * @param Boolean $sendEmail
      * @return \Illuminate\Http\Response
      */
      public function storeDeclarations(Request $request, Recipient $recipient, bool $sendEmail=true) {
        $recipientHelper = new RecipientsHelper();
        $recipientHelper->storeDeclarations($request, $recipient, $sendEmail);
      }

      /**
      * Store Personal Contact Information
      *
      * @param \Illuminate\Http\Request $request
      * @param \App\Model\Recipient $recipient
      * @return \Illuminate\Http\Response
      */
      public function storePersonalContact(Request $request, Recipient $recipient) {
        $recipientHelper = new RecipientsHelper();
        $recipientHelper->storePersonalContact($request, $recipient);

      }

      /**
      * Check if recipient is already registered (by employee number)
      * - search using Employee Number
      *
      * @param String $employee_number
      * @return \Illuminate\Http\Response
      */
      public function checkRecipientByEmployeeId (string $employee_number)
      {
        return Recipient::where('employee_number', $employee_number)
        ->select('id')
        ->whereNotNull('employee_number')
        ->firstOrFail();
      }

      /**
      * Retrieve historical recipient from the archived data
      * - search using Employee Number
      *
      * @param String $employee_number
      * @return \Illuminate\Http\Response
      */
      public function showArchivedRecipientByEmployeeId (string $employee_number)
      {
        return HistoricalRecipient::where('employee_number', $employee_number)->firstOrFail();
      }

}
