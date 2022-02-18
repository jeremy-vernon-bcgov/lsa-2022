<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RecipientController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\CommunityController;

use App\Models\Recipient;
use App\Models\Organization;
use App\Models\Award;
use App\Models\Community;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/** RECIPIENTS */

Route::controller(RecipientController::class)->group(function() {
   Route::get('/recipients/{guid}', 'show');
   Route::get('/recipients/archieved/{email}', 'showArchivedRecipientByEmail');
   Route::post('/recipients', 'store');
   Route::put('/recipients/{guid}', 'update');

   /** Registration Phases **/
    Route::post('/recipients/identification', 'storeIdentification');
    Route::post('/recipients/{recipient}/milestone', 'storeMilestone');
    Route::post('/recipient/{recipient}/retirement', 'storeRetirement');
    Route::post('/recipient/{recipient}/award', 'storeServicePins');
    Route::post('/recipient/{recipient}/declarations', 'storeDeclarations');
    Route::post('/recipients/{recipient}/contact', 'storePersonalContact');

});



route::get('/organizations/', [OrganizationController::class, 'index']);

route::get('/communities/', [CommunityController::class, 'index']);

/** AWARDS */
Route::get('/milestones/{milestone}/awards', [AwardController::class, 'getByMilestone']);
Route::get('/awards/{id}/options', [AwardController::class, 'getAwardOptions']);
