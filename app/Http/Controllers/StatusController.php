<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;
use App\Classes\StatusHelper;

class StatusController extends Controller
{

  /**
   * Retrieve registration status value of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function registrationStatus()
  {
    $processor = new StatusHelper();
    return $processor->regStatus();
  }

}
