<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecipientController;
use App\Http\Controllers\SelfRegistrationController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\PecsfController;
use App\Http\Controllers\CeremonyController;
use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TestController;

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

/** System status routes */
Route::get('/registration-status', [StatusController::class, 'registrationStatus'])
->name('reg-status');

/** Organization routes */
Route::get('/organizations/', [OrganizationController::class, 'index']);

/** Community routes */
Route::get('/communities/', [CommunityController::class, 'index']);

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
  Route::put('/recipients/assign/', [RecipientController::class, 'assign']);
  Route::put('/recipients/remind/', [RecipientController::class, 'remind']);
  Route::put('/recipients/rsvp/confirm/', [RecipientController::class, 'confirmRSVP']);
});

/** Recipients self-registration routes */
Route::controller(SelfRegistrationController::class)->group(function() {

    /** Recipient self-registration workflow routes */
    Route::get('/recipients/guid/{guid}', 'showByGUID');
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
  Route::get('/attendees/list/{ceremony}', 'getByCeremony');
  Route::get('/attendees/show/{attendee}', 'show');
  Route::get('/attendees/accommodations/{attendee}', 'getAccommodations');
  Route::put('/attendees/update/{attendee}', 'update');
  Route::get('/attendees/rsvp/{attendee}/{token}', 'getRSVP');
  Route::post('/attendees/rsvp/{attendee}/{token}', 'setRSVP');
});

/** Accommodations routes */
Route::controller(AccommodationController::class)->group(function() {
  Route::get('/accommodations/list', 'index');
  Route::get('/accommodations/show/{accommodation}', 'show');
  Route::post('/accommodations/create', 'store');
  Route::put('/accommodations/update/{accommodation}', 'update');
  Route::get('/accommodations/delete/{accommodation}', 'destroy');
});

/** Reports routes */
Route::controller(ReportsController::class)->group(function() {
    Route::get('/reports/awards/summary/{format}', 'awardsSummary');
    Route::get('/reports/awards/pecsf/{format}', 'pecsfSummary');
    Route::get('/reports/recipients/summary/{format}', 'recipientsList');
    Route::get('/reports/attendees/summary/{format}', 'attendeesList');
});

/** Testing routes */
Route::controller(TestController::class)->group(function() {
    Route::put('/test/email/reminder', 'sendTestReminder');
    Route::put('/test/email/invitation', 'sendTestInvitation');
    Route::put('/test/email/rsvp-confirmation', 'sendTestRSVPConfirmation');
});
