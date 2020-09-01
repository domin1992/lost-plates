<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Front\MapsController@index')->name('front.maps.index');

Route::prefix('ajax')->group(function(){
    Route::get('/markers', 'Front\MarkersController@ajaxIndex')->name('front.ajax.markers.index');
    Route::get('/markers/{id}/phone-number', 'Front\MarkersController@ajaxGetPhoneNumber')->name('front.ajax.markers.get-phone-number');
    Route::get('/markers/{id}/email', 'Front\MarkersController@ajaxGetEmail')->name('front.ajax.markers.get-email');
    Route::post('/markers', 'Front\MarkersController@ajaxStore')->name('front.ajax.markers.store');
});
