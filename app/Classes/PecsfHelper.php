<?php
namespace App\Classes;
use App\Models\Attendee;
use App\Models\;
use App\Classes\MailHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PecsfHelper
{

  /**
  * Get array of charities indexed by ID
  *
  * @return Array
  */

  private function getIndexedCharities() {
    $charities = PecsfCharity::all()->keyBy('id');
    $indexed = array();
    foreach ($charities as $charity) {
      $indexed[$charity->id] = $charity;
    }
    return $indexed;
  }

  /**
  * Get assigned ceremony for attendee
  *
  * @return Array
  */

  public function getIndexedRegions() {
    return;
  }


}
