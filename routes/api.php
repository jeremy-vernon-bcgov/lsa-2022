<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecipientController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\PecsfController;
use App\Http\Controllers\CeremonyController;
use App\Http\Controllers\ReportsController;

use App\Models\Recipient;
use App\Models\Organization;
use App\Models\Award;
use App\Models\Community;
use App\Models\User;

use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

if (App::environment('production')) {
    URL::forceScheme('https');
}

/** Organization routes */
route::get('/organizations/', [OrganizationController::class, 'index']);

/** Community routes */
route::get('/communities/', [CommunityController::class, 'index']);

/** Awards routes */
Route::get('/milestones/{milestone}/awards', [AwardController::class, 'getByMilestone']);
// Route::get('/awards/{id}/options', [AwardController::class, 'getAwardOptions']);

/** PECSF routes */
Route::get('/pecsf/charities', [PecsfController::class, 'getCharities']);
Route::get('/pecsf/regions', [PecsfController::class, 'getRegions']);

/** Recipient administrator routes */
Route::middleware('auth:sanctum')->group(function() {
  Route::get('/recipients/list', [RecipientController::class, 'index']);
  Route::get('/recipients/view/{recipient}', [RecipientController::class, 'show']);
  Route::get('/recipients/show/{recipient}', [RecipientController::class, 'show']);
  Route::post('/recipients/create', [RecipientController::class, 'store']);
  Route::put('/recipients/update/{recipient}', [RecipientController::class, 'update']);
  Route::get('/recipients/delete/{recipient}', [RecipientController::class, 'destroy']);
  Route::put('/recipients/assign/{recipient}', [RecipientController::class, 'assign']);
  Route::get('/recipients/send-confirmation/{recipient}', [RecipientController::class, 'sendConfirmation']);
});

/** Recipients self-registration routes */
Route::controller(RecipientController::class)->group(function() {

    /** Recipient self-registration workflow routes */
    Route::get('/recipients/{guid}', 'showByGUID');
    Route::get('/recipients/employee_number/{employee_number}', 'checkRecipientByEmployeeId');
    Route::get('/recipients/archived/{employee_number}', 'showArchivedRecipientByEmployeeId');

    /** Self-Registration Phases routes **/
    Route::post('/recipients/identification', 'storeIdentification');
    Route::post('/recipients/{recipient}/milestone', 'storeMilestone');
    Route::post('/recipients/{recipient}/retirement', 'storeRetirement');
    Route::post('/recipients/{recipient}/award', 'storeAward');
    Route::post('/recipients/{recipient}/servicepins', 'storeServicePins');
    Route::post('/recipients/{recipient}/declarations', 'storeDeclarations');
    Route::post('/recipients/{recipient}/contact', 'storePersonalContact');
});

/** Awards routes */
Route::controller(AwardController::class)->group(function() {
  Route::get('/awards/list', 'index');
  Route::get('/awards/show/{award}', 'show');
  Route::post('/awards/create', 'store');
  Route::put('/awards/update/{award}', 'update');
  Route::get('/awards/delete/{award}', 'destroy');
});

/** Ceremonies routes */
Route::controller(CeremonyController::class)->group(function() {
  Route::get('/ceremonies/list', 'index');
  Route::get('/ceremonies/show/{ceremony}', 'show');
  Route::post('/ceremonies/create', 'store');
  Route::put('/ceremonies/update/{ceremony}', 'update');
  Route::get('/ceremonies/delete/{ceremony}', 'destroy');
});

/** Attendees routes */
Route::controller(AttendeeController::class)->group(function() {
  Route::get('/attendees/list', 'index');
  Route::get('/attendees/show/{attendee}', 'show');
});

/** Reports routes */
Route::controller(ReportsController::class)->group(function() {
    Route::get('/reports/awards/summary/{format}', 'awardsSummary');
    Route::get('/reports/recipients/summary/{format}', 'recipientsSummary');
});
