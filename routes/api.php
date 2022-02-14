<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/recipients/{guid}', [RecipientController::class, 'show']);
Route::get('/recipients/archived/{email}', [RecipientController::class, 'showArchivedRecipientByEmail']);
Route::post('/recipients', [RecipientController::class, 'store']);
Route::put('/recipients/{guid}', [RecipientController::class, 'update']);

/** Registration Phases */
Route::post('/recipients/{guid}/identification', [RecipientController::class,'storeIdentification']);
Route::post('/recipients/{guid}/milestone', [RecipientController::class, 'storeMilestone']);
Route::post('/recipients/{guid}/retirement', [RecipientController::class, 'storeRetirement']);
Route::post('/recipients/{guid}/award', [RecipientController::class, 'storeAward']);
Route::post('/recipients/{guid}/servicePins', [RecipientController::class, 'storeServicePins']);
Route::post('/recipients/{guid}/declarations', [RecipientController::class, 'storeDeclarations']);
Route::post('/recipients/{guid}/contact', [RecipientController::class, 'storePersonalContact']);
Route::put('/recipients/{guid}/confirm', [RecipientController::class, 'updateConfirmation']);


/** AWARDS */
Route::get('/milestones/{milestone}/awards', [AwardController::class, 'getByMilestone']);
Route::get('/awards/{id}/options', [AwardController::class, 'getAwardOptions']);
