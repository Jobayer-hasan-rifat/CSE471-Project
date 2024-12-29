<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VenueController;
use App\Http\Controllers\Api\EventController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Venue Availability Check
    Route::post('/venues/{venue}/check-availability', [VenueController::class, 'checkAvailability'])
        ->name('api.venues.check-availability');

    // Event Calendar API Routes
    Route::get('/events/calendar', [EventController::class, 'calendar'])->name('api.events.calendar');
    Route::get('/venues/availability', [VenueController::class, 'checkAvailability'])->name('api.venues.availability');
});
