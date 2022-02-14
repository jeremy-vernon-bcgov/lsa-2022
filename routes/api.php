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
Route::get('/recipients', [RecipientController::class, 'index']);
Route::get('/recipients/archived/{email}', [RecipientController::class, 'showArchivedRecipientByEmail']);
Route::get('/recipients/{guid}', [RecipientController::class, 'show']);
Route::post('/recipients', [RecipientController::class, 'store']);
Route::put('/recipients/{guid}', [RecipientController::class, 'update']);


/** AWARDS */
Route::get('/milestones/{milestone}/awards', [AwardController::class, 'api_getByMilestone']);
