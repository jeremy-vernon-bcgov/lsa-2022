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


class AwardController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    return Award::all();
  }

  /**
  * Retrieve full record of award.
  *
  * @param Award $award
  */

  public function show (Award $award) {
    $this->authorize('view', Award::class);
    return $award;
  }

  /**
  * Create new award.
  *
  * @param Award $award
  */

  public function store (Request $request) {
    $this->authorize('create', Award::class);

    // create new award
    $award = Award::create([
      'name' => $request->input('name'),
      'short_name' => $request->input('short_name'),
      'options' => $request->input('options'),
      'quantity' => $request->input('quantity'),
    ]);
    $award->save();
    return $award;
  }

  /**
  * Update record of award.
  *
  * @param Award $award
  */

  public function update (Request $request, Award $award) {
    $this->authorize('update', Award::class);

    // update award date time
    $award->scheduled_datetime = $request->input('scheduled_datetime');
    $award->save();
    return $award;
  }

  /**
  * Delete record of award.
  *
  * @param Award $award
  */

  public function destroy (Request $request, Award $award) {
    $this->authorize('delete', Award::class);
    $award->delete();
    return $award;
  }

  /**
  * Display a list of awards associated with a milestone
  *
  * @param Integer $milestone
  * @return \Illuminate\Http\Response
  */

  public function getByMilestone($milestone)
  {
    return Award::where('milestone', $milestone)->get();
  }

  /**
  * Return options for an award
  *
  * @param Integer $milestone
  * @return \Illuminate\Http\Response
  */

  public function getAwardOptions(Award $award)
  {
    return $award->get();
  }



}
