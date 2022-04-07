<?php
namespace App\Classes;
use Illuminate\Support\Facades\Log;
class ReportsHelper
{

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

    $includedOptions = ['certificate', 'engraving', 'honour'];

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
              function($a) use (&$return, $includedOptions) {
                if (in_array($a->key, $includedOptions)) $return .= "$a->key: $a->value\n";
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

    // process awards to expand options
    foreach ($awards as $key => $award) {
      // handle award options
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
  public function getAwardTotals($selections, $options){

    $ignoreOptions = ['engraving'];
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
      $milestoneTotals = array(
        '25' => 0,
        '30' => 0,
        '35' => 0,
        '40' => 0,
        '45' => 0,
        '50' => 0,
        'extras' => 0,

      );
      $milestone = $option['milestone'];

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
            $milestoneTotals['extras'] -= 1;
          }
        }
      }
      $results[] = $option + $milestoneTotals;
    }
    // add totals row
    $results[] = $totals;
    return $results;
  }

}
