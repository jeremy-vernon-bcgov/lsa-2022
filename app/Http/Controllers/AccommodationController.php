<?php

namespace App\Http\Controllers;

use App;
use App\Models\Accommodation;
use App\Models\Recipient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ramsey\Uuid\Type\Integer;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Classes\ReportsHelper;

use Illuminate\Support\Facades\Log;


class AccommodationController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    return Accommodation::all();
  }

  /**
  * Retrieve full record of accommodation.
  *
  * @param Accommodation $accommodation
  */

  public function show (Accommodation $accommodation) {
    $this->authorize('view', Accommodation::class);
    return $accommodation;
  }

  /**
  * Create new accommodation.
  *
  * @param Accommodation $accommodation
  */

  public function store (Request $request) {
    $this->authorize('create', Accommodation::class);

    // create new Accommodation
    $accommodation = Accommodation::create([
      'short_name' => $request->input('short_name'),
      'full_name' => $request->input('full_name'),
      'description' => $request->input('description'),
      'type' => $request->input('type'),
    ]);
    $accommodation->save();
    return $accommodation;
  }

  /**
  * Update record of Accommodation.
  *
  * @param Accommodation $accommodation
  */

  public function update (Request $request, Accommodation $accommodation) {
    $this->authorize('update', Accommodation::class);

    // update accommodation fields
    $accommodation->type = $request->input('type');
    $accommodation->short_name = $request->input('short_name');
    $accommodation->full_name = $request->input('full_name');
    $accommodation->description = $request->input('description');

    $accommodation->save();
    return $accommodation;
  }

  /**
  * Delete record of accommodation.
  *
  * @param Accommodation $accommodation
  */

  public function destroy (Request $request, Accommodation $accommodation) {
    $this->authorize('delete', Accommodation::class);
    $accommodation->delete();
    return $accommodation;
  }

}
