<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Api\AppointmentController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Auth Endpoints
Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);

// Appointments Endpoints
Route::apiResource('appointment', AppointmentController::class)
    ->middleware('auth:api');

/**
 * apiResource:
Verb          Path                                       Action  Route Name
GET           /appointments                              index   appointments.index
POST          /appointments                              store   appointments.store
GET           /appointments/{appointment}                show    appointments.show
PUT|PATCH     /appointments/{appointment}                update  appointments.update
DELETE        /appointments/{appointment}                destroy appointments.destroy
 */
