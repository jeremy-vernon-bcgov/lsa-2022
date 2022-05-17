<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ceremony;
use App\Models\Recipient;
use App\Models\Guest;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class CeremonyController extends Controller
{

  /**
  * Displays a list of all guests
  *
  * @param Guest $ceremony
  */

  public function index () {
    $this->authorize('viewAny', Ceremony::class);
    return Guest::with('recipients')->all();
  }

  /**
  * Retrieve guest record.
  *
  * @param Guest $ceremony
  */

  public function show (Guest $guest) {
    $this->authorize('view', Guest::class);
    return $guest;
  }

  /**
  * Create new guest.
  *
  * @param Guest $ceremony
  */

  public function store (Request $request, Recipient $recipient) {
    $this->authorize('create', Guest::class);
  }

}
