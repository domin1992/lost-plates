<?php

use App\Http\Controllers\Front\MarkersController;
use App\Http\Controllers\Front\MediaController;
use App\Http\Controllers\Front\MarkerCommentsController;

Route::prefix('ajax')->group(function () {
    Route::get('/markers', [MarkersController::class, 'ajaxIndex'])->name('front.ajax.markers.index');
    Route::get('/markers/{id}', [MarkersController::class, 'ajaxShow'])->name('front.ajax.markers.show');
    Route::get('/markers/{id}/phone-number', [MarkersController::class, 'ajaxGetPhoneNumber'])->name('front.ajax.markers.get-phone-number');
    Route::post('/markers/{id}/contact', [MarkersController::class, 'ajaxSubmitContact'])->name('front.ajax.markers.contact');
    Route::post('/markers', [MarkersController::class, 'ajaxStore'])->name('front.ajax.markers.store');
    Route::post('/media', [MediaController::class, 'ajaxStore'])->name('front.ajax.media.store');
    Route::delete('/media/{id}', [MediaController::class, 'ajaxDestroy'])->name('front.ajax.media.destroy');
    Route::post('/markers/{id}/comments', [MarkerCommentsController::class, 'ajaxStore'])->name('front.ajax.markers.comments.store');
});
