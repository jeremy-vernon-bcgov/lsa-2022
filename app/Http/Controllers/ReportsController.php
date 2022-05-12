<?php

namespace App\Http\Controllers;

use App;
use App\Models\Recipient;
use App\Models\Award;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ramsey\Uuid\Type\Integer;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Classes\ReportsHelper;

use Illuminate\Support\Facades\Log;


class ReportsController extends Controller
{

  /**
  * Recipient Data Export: Exports all recipient data [CSV]
  *
  * @return \Illuminate\Http\Response
  */
  public function recipientsSummary(String $format)
  {

    $this->authorize('export', Recipient::class);
    $authUser = auth()->user();

    // get recipients
    $recipients = Recipient::with([
      'personalAddress',
      'supervisorAddress',
      'officeAddress',
      'awards'])
      ->declared($authUser)
      ->userOrgs($authUser)
      ->metadata()
      ->get()
      ->toArray();

      // init resultant filtered array
      $filteredRecipients = array();

      // create helper utility instance
      $processor = new ReportsHelper();

      // prepare recipient data for reporting
      // - attaches award selections/options info
      foreach ($recipients as $recipient) {
        $filteredRecipients[] = $processor->filterRecipientData($recipient);
      }

      // stream CSV data
      return $processor->csv($filteredRecipients, 'lsa-recipients.csv');

    }

    /**
    * Award summary report
    *
    * @return \Illuminate\Http\Response
    */
    public function awardsSummary(string $format)
    {
      $this->authorize('report', Award::class);

      // retrieve awards list
      $awards = Award::select('id', 'name', 'milestone', 'options', 'quantity')
      ->orderBy('milestone')
      ->orderBy('name')
      ->get();

      // get recipient selections
      // - exclude draft registrations
      $selections = Recipient::join(
        'award_recipient',
        'recipients.id','=','award_recipient.recipient_id')
        ->select(
          'recipients.id',
          'award_recipient.award_id AS award',
          'award_recipient.options AS options')
        ->where('recipients.is_declared', 1)
        ->get();

        // calculate award totals for output
        $processor = new ReportsHelper();
        $totals = $processor->getAwardTotals($selections, $awards);

        if ($format === 'pdf') {
          $pdf = PDF::loadView('documents.awardsSummaryList', compact('totals'));
          $pdf->setPaper('A4', 'landscape');
          return $pdf->download('awards-summary-list.pdf');
        }
        elseif ($format === 'csv') {
          return $processor->csv($totals, 'lsa-awards-summary.csv');
        }
        else {
          return $totals;
      }
    }

    /**
    * PECSF Donations summary report
    *
    * @return \Illuminate\Http\Response
    */
    public function pecsfSummary(string $format)
    {
      $this->authorize('report', Award::class);
      $processor = new ReportsHelper();
      $selections = $processor->getPECSFSelections();

      if ($format === 'pdf') {
        $pdf = PDF::loadView('documents.pecsfSummaryList', compact('selections'));
        $pdf->setPaper('tabloid', 'landscape');
        return $pdf->download('pecsf-summary-list.pdf');
      }
      elseif ($format === 'csv') {
        return $processor->csv($selections, 'lsa-pecsf-summary.csv');
      }
      else {
        return $selections;
      }

    }

    /**
    * Generate Recipient Reports
    *
    * @param \Illuminate\Http\Request $request
    * @param \App\Model\Recipient $recipient
    * @return \Illuminate\Http\Response
    */

    public function generateAllRecipientReport() {

      $this->authorize('viewAny', Recipient::class);

      $pdf = PDF::loadView('documents.recipientsByMinistry');
      return $pdf->download('recipients-by-ministry.pdf');

    }

}
