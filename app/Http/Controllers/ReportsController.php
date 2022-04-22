<?php

namespace App\Http\Controllers;

use App;
use App\Models\Award;
use App\Models\Recipient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ramsey\Uuid\Type\Integer;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Classes\ReportsHelper;

use Illuminate\Support\Facades\Log;


class ReportsController extends Controller
{

  /**
  * Return a filtered list of Recipients as CSV.
  *
  * @return \Illuminate\Http\Response
  */
  public function recipientsSummary(String $format)
  {

    $this->authorize('export', Recipient::class);

    // get authenticated user
    $authUser = auth()->user();

    // get recipients
    $recipients = Recipient::with([
      'personalAddress',
      'supervisorAddress',
      'officeAddress',
      'awards'])
      ->declared($authUser)
      ->userOrgs($authUser)
      ->historical()
      ->orgs()
      ->get()
      ->toArray();

      $name = 'lsa-recipients.csv';
      $headers = [
        'Content-Disposition' => 'attachment; filename='. $name,
      ];

      // create helper utility instance
      $processor = new ReportsHelper();

      // stream CSV data
      return response()->stream(function() use($recipients, $processor){
        $file = fopen('php://output', 'w+');

        // process data rows
        foreach ($recipients as $key => $recipient) {

          // extract award and options
          $award = $processor->getRecipientAwards($recipient);

          $line = array(
            'employee_number' => strval($recipient['employee_number']),
            'first_name' => $recipient['first_name'],
            'last_name' => $recipient['last_name'],
            'government_email' => $recipient['government_email'],
            'government_phone_number' => $recipient['government_phone_number'],
            'personal_email' => $recipient['personal_email'],
            'organization' => $recipient['organization_name'],
            'organization_short_name' => $recipient['organization_short_name'],
            'branch_name' => $recipient['branch_name'],

            'milestone' => strval($recipient['milestones']),
            'qualifying_year' => strval($recipient['qualifying_year']),
            'retirement_date' => $recipient['retirement_date'],

            'office_prefix' => is_array($recipient['office_address'])
            ? $recipient['office_address']['prefix'] : '',
            'office_street_address' => is_array($recipient['office_address'])
            ? $recipient['office_address']['street_address'] : '',
            'office_postal_code' => is_array($recipient['office_address'])
            ? $recipient['office_address']['postal_code'] : '',
            'office_community' => is_array($recipient['office_address'])
            ? $recipient['office_address']['community'] : '',

            'personal_phone_number' => $recipient['personal_phone_number'],
            'personal_email' => $recipient['personal_email'],
            'personal_prefix' => is_array($recipient['personal_address'])
            ? $recipient['personal_address']['prefix'] : '',
            'personal_street_address' => is_array($recipient['personal_address'])
            ? $recipient['personal_address']['street_address'] : '',
            'personal_postal_code' => is_array($recipient['personal_address'])
            ? $recipient['personal_address']['postal_code'] : '',
            'personal_community' => is_array($recipient['personal_address'])
            ? $recipient['personal_address']['community'] : '',

            'supervisor_last_name' => $recipient['supervisor_last_name'],
            'supervisor_first_name' => $recipient['supervisor_first_name'],
            'supervisor_email' => $recipient['supervisor_email'],
            'supervisor_pobox' => is_array($recipient['supervisor_address'])
            ? $recipient['supervisor_address']['pobox'] : '',
            'supervisor_prefix' => is_array($recipient['supervisor_address'])
            ? $recipient['supervisor_address']['prefix'] : '',
            'supervisor_street_address' => is_array($recipient['supervisor_address'])
            ? $recipient['supervisor_address']['street_address'] : '',
            'supervisor_postal_code' => is_array($recipient['supervisor_address'])
            ? $recipient['supervisor_address']['postal_code'] : '',
            'supervisor_community' => is_array($recipient['supervisor_address'])
            ? $recipient['supervisor_address']['community'] : '',

            'bcgeu' => $recipient['is_bcgeu_member'] ? 'Yes' : 'No',
            'ceremony_opt_out' => $recipient['ceremony_opt_out'] ? 'Yes' : 'No',
            'survey_participation' => $recipient['survey_participation'] ? 'Yes' : 'No',

            'award' => $award['name'],
            'award_options' => $award['options'],

            'admin_notes' => $recipient['admin_notes'],

            'updated_at' => $recipient['updated_at'],
            'created_at' => $recipient['created_at'],
          );

          // create header row on first record
          if ($key === 0) fputcsv($file, array_keys($line));
          // create record row
          fputcsv($file, $line);
        }

        $blanks = array("\t","\t","\t","\t");
        fputcsv($file, $blanks);
        $blanks = array("\t","\t","\t","\t");
        fputcsv($file, $blanks);
        $blanks = array("\t","\t","\t","\t");
        fputcsv($file, $blanks);
        fclose($file);
      }, 200, $headers);
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

      $processor = new ReportsHelper();
      $selections = $processor->getAwardOptions($awards);

      // get recipient selections
      $selected = Recipient::join(
        'award_recipient',
        'recipients.id','=','award_recipient.recipient_id'
        )
        ->select(
          'recipients.id',
          'award_recipient.award_id AS award',
          'award_recipient.options AS options'
          )
          ->get();

          $totals = $processor->getAwardTotals($selected, $selections);

          if ($format === 'pdf') {
            $pdf = PDF::loadView('documents.awardsSummaryList', compact('totals'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download('awards-summary-list.pdf');
          }
          else {
            return $totals;
          }
        }

      }
