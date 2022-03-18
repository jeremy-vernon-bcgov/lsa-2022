<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecipientController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\PecsfController;

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

/** Recipients routes */

Route::controller(RecipientController::class)->group(function() {

    /** Recipient administrator routes */
    Route::get('/recipients/list', 'index')->middleware('auth:web');
    Route::get('/recipients/view/{recipient}', 'show');
    Route::get('/recipients/show/{recipient}', 'show');
    Route::post('/recipients/create', 'store');
    Route::put('/recipients/update/{recipient}', 'update');
    Route::get('/recipients/delete/{recipient}', 'disable');

    /** Recipient self-registration workflow routes */
    Route::get('/recipients/{guid}', 'showByGUID');
    Route::get('/recipients/reset/{recipient}', 'reset');
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
