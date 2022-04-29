<?php
namespace App\Classes;
use App\Models\Recipient;
use App\Models\Award;
use App\Models\PecsfCharity;
use App\Models\PecsfRegion;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ReportsHelper
{

  /**
  * Generate CSV download stream from array
  *
  * @param Array
  * @param String
  * @return Response
  */
  public function csv($rows, $filename) {
    $headers = [
      'Content-Disposition' => 'attachment; filename='. $filename,
    ];

    // return CSV data stream for download
    return response()->stream(function() use($rows){
        $file = fopen('php://output', 'w+');
        // process data rows
        foreach ($rows as $key => $row) {
          // create header row on first record
          if ($key === 0) fputcsv($file, array_keys($row));
          fputcsv($file, $row);
        }
        fclose($file);

    }, 200, $headers);
  }

  /**
  * Get flattened award selections from recipient
  *
  * @return Array
  */
  public function getRecipientAwards($recipient) {

    $result = array(
      'name' => '',
      'options' => ''
    );

    $excludedOptions = [
      'pecsfPool',
    ];

    // extract awards data (if exists)
    if (is_array($recipient['awards'])) {
      foreach ($recipient['awards'] as $award) {
        $result['name'] .= $award['name'].' ';
        // check for award options in pivot
        if (isset($award['pivot']) && !empty($award['pivot']['options'])) {
          $options = json_decode($award['pivot']['options']);
          // extract award option selections (if exist)
          if ( isset($options->selections) ){
            $return = '';
            // include only specified options in returned data
            array_walk_recursive(
              $options->selections,
              function($a) use (&$return, $excludedOptions) {
                if (!in_array($a->key, $excludedOptions)) $return .= "$a->key: $a->value\n";
              }
            );
            $result['options'] = $return;
          }
        }
      }
    }
    return $result;
  }

  /**
  * Flatten award selections into array
  *
  * @return Array
  */
  private function getAwardSelections($option, $selections, $award) {
    // init conditions
    if (count($selections)===0) {
      foreach ($option as $key => $choice) {
        $selections[] = array(
          'id' => $award->id,
          'name' => "{$award->milestone} - {$award->name} {$choice->text}",
          'milestone' => $award->milestone,
          'quantity' => $award->quantity,
          'options' => array($choice->value)
        );
      }
    }
    else {
      $tmp = array();
      foreach ($option as $key => $choice) {
        foreach ($selections as $key => $selection) {
          // include option key
          $tmpOptions = $selection['options'];
          array_push($tmpOptions, $choice->value);
          // update selections array
          $tmp[] = array(
            'id' => $award->id,
            'name' => "{$selection['name']} | {$choice->text}",
            'milestone' => $award->milestone,
            'quantity' => $award->quantity,
            'options' => $tmpOptions
          );
        }
      }
      $selections = $tmp;
    }
    return $selections;
  }

  /**
  * Flatten award options into array
  *
  * @return Array
  */
  public function getAwardOptions($awards){

    $ignoreOptions = ['engraving'];

    // iterate over awards to flatten options
    foreach ($awards as $key => $award) {
      // handle awards with options
      $selections = [];
      if (isset($award->options) && !empty($award->options)) {
        $options = json_decode($award->options);
        // iterate over award options
        foreach ($options as $optionType => $option) {
          if (is_array($option)) {
            $selections = $this->getAwardSelections($option, $selections, $award);
          }
          elseif (!in_array($optionType, $ignoreOptions)) {
            // award option has single selection
            $singleOptions = array(
              'certificate' => 'Framed Certificate and ',
              'pecsf' => '',
            );
            $awardOption = isset($singleOptions[$optionType]) ? $singleOptions[$optionType] : '';
            $awardNameWithOptions = "{$award->milestone} - {$awardOption}{$award->name}";
            $filteredOptions[] = array(
              'id' => $award->id,
              'name' => $awardNameWithOptions,
              'milestone' => $award->milestone,
              'quantity' => $award->quantity,
              'options' => array($optionType)
            );
          }
        }
      }
      else {
        // award does not have options
        $filteredOptions[] = array(
          'id' => $award->id,
          'name' => "{$award->milestone} - {$award->name}",
          'milestone' => $award->milestone,
          'quantity' => $award->quantity,
          'options' => []
        );
      }
      $filteredOptions = array_merge($filteredOptions, $selections);
    }
    return $filteredOptions;
  }

  /**
  * Compute award totals for each milestone and option
  *
  * @return Array
  */
  public function getAwardTotals($selections, $awards){

    // flatten awards to include options
    $options = $this->getAwardOptions($awards);
    // ignore certain options in the tallies
    $ignoreOptions = ['engraving', 'certificate'];
    $results = [];

    // award totals over all award selections
    $totals = array(
      'name' => 'Totals:',
      '25' => 0,
      '30' => 0,
      '35' => 0,
      '40' => 0,
      '45' => 0,
      '50' => 0,
    );

    // iterate over award options
    foreach ($options as $option) {

      $milestone = $option['milestone'];
      $milestoneTotals = array(
        '25' => 0,
        '30' => 0,
        '35' => 0,
        '40' => 0,
        '45' => 0,
        '50' => 0,
        'extras' => 0,
      );

      // iterate over recipient award selections
      foreach ($selections as $selection) {
        // match awards
        if ($selection['award'] === $option['id']) {
          $selectedOptions = json_decode($selection->options);
          $isMatch = true;
          // match any selected options with award options
          if (isset($selectedOptions->selections)) {
            foreach ($selectedOptions->selections as $selected) {
              // skip all options checks for pecsf donations
              if ($selected->key === 'pecsfPool') break;
              // skip this option check if ignored
              if (in_array($selected->key, $ignoreOptions)) continue;
              // check if selected option is among award options
              $isMatch = $isMatch && in_array($selected->value, $option['options']);
            }
          }
          if ($isMatch) {
            $milestoneTotals[$milestone] += 1;
            $totals[$milestone] += 1;
            $milestoneTotals['extras'] += 1;
          }
        }
      }
      $results[] = array('name' => $option['name']) + $milestoneTotals;
    }
    // add totals row
    $results[] = $totals;
    return $results;
  }



  /**
  * Prepare recipient data for reporting
  *
  * @return Array
  */
  public function filterRecipientData($recipient){

    // extract award and options
    $award = $this->getRecipientAwards($recipient);

    return array(
      'employee_number' => strval($recipient['employee_number']),
      'first_name' => $recipient['first_name'],
      'last_name' => $recipient['last_name'],
      'government_email' => $recipient['government_email'],
      'government_phone_number' => $recipient['government_phone_number'],
      'personal_email' => $recipient['personal_email'],
      'organization' => $recipient['organization_name'],
      'organization_short_name' => $recipient['organization_short_name'],
      'branch_name' => $recipient['branch_name'],

      // 'historical' => strval($recipient['historical']),
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
  }


  /**
  * Extract PECSF award options from given award selections
  *
  * @return Array
  */
  public function getPECSFSelections(){

    // parse options for PECSF charities and regions
    function parsePecsfOptions($jsonOptions) {
      $charities = PecsfCharity::all()->keyBy('id');
      $regions = PecsfRegion::all()->keyBy('id');

      $optionData = json_decode($jsonOptions);

      if (!isset($optionData->selections)) Log::info('PECSF', array('Options' => $optionData,));

      // index selected options by ID
      $options = isset($optionData->selections)
        ? $optionData->selections
        : array();

      $indexedOptions = array();
      foreach ($options as $option) {
        $indexedOptions[$option->key] = $option->value;
      }
      return array(
        'pecsf_pool' => isset($indexedOptions['pecsfPool']) && !empty($indexedOptions['pecsfPool'])
        ? 'Yes' : 'No',
        'pecsf_region_1' => isset($indexedOptions['pecsfRegion1']) && !empty($indexedOptions['pecsfRegion1'])
        ? $regions[$indexedOptions['pecsfRegion1']]->name : null,
        'pecsf_region_2' => isset($indexedOptions['pecsfRegion2']) && !empty($indexedOptions['pecsfRegion2'])
        ? $regions[$indexedOptions['pecsfRegion2']]->name : null,
        'pecsf_charity_1' => isset($indexedOptions['pecsfCharity1']) && !empty($indexedOptions['pecsfCharity1'])
        ? $charities[$indexedOptions['pecsfCharity1']]->name : null,
        'pecsf_charity_1_vendor' => isset($indexedOptions['pecsfCharity1']) && !empty($indexedOptions['pecsfCharity1'])
        ? $charities[$indexedOptions['pecsfCharity1']]->vendor_code : null,
        'pecsf_charity_2' => isset($indexedOptions['pecsfCharity2']) && !empty($indexedOptions['pecsfCharity2'])
        ? $charities[$indexedOptions['pecsfCharity2']]->name : null,
        'pecsf_charity_2_vendor' => isset($indexedOptions['pecsfCharity2']) && !empty($indexedOptions['pecsfCharity2'])
        ? $charities[$indexedOptions['pecsfCharity2']]->vendor_code : null,
      );
    }

    // get recipient PECSF selections
    $selections = Recipient::with('personalAddress')
    ->join(
      'award_recipient',
      'recipients.id','=','award_recipient.recipient_id')
      ->join('awards',
      'award_recipient.award_id', '=', 'awards.id')
      ->where([
        ['awards.short_name', 'LIKE', '%pecsf%'],
        ['recipients.is_declared', '=', 1]
      ])
      ->select(
        'recipients.*',
        'awards.*',
        'award_recipient.award_id AS award',
        'award_recipient.options AS options')
        ->get();

        $results = [];

        // iterate over recipient award selections
        foreach ($selections as $selection) {
          // extract and index PECSF options
          $options = parsePecsfOptions($selection->options);

          // compose resultant data array
          $results[] = array(
            'award_year' => $selection['qualifying_year'],
            'employee_number' => $selection['employee_number'],
            'first_name' => $selection['first_name'],
            'last_name' => $selection['last_name'],
            'organization' => $selection['organization']['name'],
            'community' => isset($selection['personalAddress']['community'])
            ? $selection['personalAddress']['community']
            : '',
            'milestone' => $selection['milestone'],
            'pecsf_pool' => $options['pecsf_pool'],
            'pecsf_region_1' => $options['pecsf_region_1'],
            'pecsf_region_2' => $options['pecsf_region_2'],
            'pecsf_charity_1' => $options['pecsf_charity_1'],
            'pecsf_charity_1_vendor' => $options['pecsf_charity_1_vendor'],
            'pecsf_charity_2' => $options['pecsf_charity_2'],
            'pecsf_charity_2_vendor' => $options['pecsf_charity_2_vendor'],
          );
        }
        return $results;
      }

    }
