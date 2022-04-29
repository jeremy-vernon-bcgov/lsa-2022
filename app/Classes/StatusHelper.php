<?php
namespace App\Classes;
use App\Models\Option;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class StatusHelper
{

  /**
  * Return self-registration system status
  */
  public function regStatus() {
    return Option::where('key', 'self-registration')->first();
  }

  /**
  * Is self-registration currently active?
  */
  public function isRegistrationActive() {
    return Option::where([
      ['key', '=', 'self-registration'],
      ['value', '=', '1']
      ])->exists();
  }

}
