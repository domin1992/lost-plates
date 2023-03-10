<?php

use App\Http\Controllers\Front\MapsController;
use App\Http\Controllers\Front\MarkersController;
use App\Http\Controllers\Front\MediaController;
use App\Models\Marker;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('', [MapsController::class, 'index'])->name('front.maps.index');

Route::get('{type}/{plateNumber}', [MarkersController::class, 'show'])
    ->where('type', Marker::TYPE_FOUND . '|' . Marker::TYPE_LOST)
    ->name('markers.show');

Route::get('{type}', [MarkersController::class, 'index'])
    ->where('type', Marker::TYPE_FOUND . '|' . Marker::TYPE_LOST)
    ->name('markers.show');

Route::prefix('ajax')->group(function () {
    Route::get('/markers', [MarkersController::class, 'ajaxIndex'])->name('front.ajax.markers.index');
    Route::get('/markers/{id}', [MarkersController::class, 'ajaxShow'])->name('front.ajax.markers.show');
    Route::get('/markers/{id}/phone-number', [MarkersController::class, 'ajaxGetPhoneNumber'])->name('front.ajax.markers.get-phone-number');
    Route::post('/markers', [MarkersController::class, 'ajaxStore'])->name('front.ajax.markers.store');
    Route::post('/media', [MediaController::class, 'ajaxStore'])->name('front.ajax.media.store');
    Route::delete('/media/{id}', [MediaController::class, 'ajaxDestroy'])->name('front.ajax.media.destroy');
});
