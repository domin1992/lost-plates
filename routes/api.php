<?php

use App\Http\Controllers\Api\MarkerCommentsController;
use App\Http\Controllers\Api\MarkersController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\PlatesController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('markers')
    ->controller(MarkersController::class)
    ->group(function () {
        Route::get('', 'index');
        Route::post('', 'store')
            ->middleware('throttle:apiStore');
        Route::get('{id}', 'show');
        Route::get('{id}/phone-number', 'phoneNumber')
            ->middleware('throttle:apiStore');
        Route::post('{id}/submit-contact', 'submitContact')
            ->middleware('throttle:apiStore');

        Route::prefix('{markerId}/marker-comments')
            ->controller(MarkerCommentsController::class)
            ->group(function () {
                Route::get('', 'index');
                Route::post('', 'store')
                    ->middleware('throttle:apiStore');;
            });
    });

Route::prefix('media')
    ->controller(MediaController::class)
    ->group(function () {
        Route::post('', 'store')
            ->middleware('throttle:apiStore');
        Route::delete('{id}', 'destroy')
            ->middleware('throttle:apiStore');
    });

Route::prefix('plates')
    ->controller(PlatesController::class)
    ->group(function () {
        Route::get('{id}', 'show');
        Route::get('/number/{number}', 'showByNumber');
    });
