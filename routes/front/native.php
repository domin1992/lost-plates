<?php

use App\Http\Controllers\Front\MapsController;
use App\Http\Controllers\Front\MarkersController;
use App\Models\Marker;

Route::get('{type}/{plateNumber}', [MarkersController::class, 'show'])
    ->where('type', Marker::TYPE_FOUND . '|' . Marker::TYPE_LOST)
    ->name('front.markers.show');

Route::get('{type}', [MarkersController::class, 'index'])
    ->where('type', Marker::TYPE_FOUND . '|' . Marker::TYPE_LOST)
    ->name('front.markers.index');

Route::get('', [MapsController::class, 'index'])
    ->name('front.maps.index');
