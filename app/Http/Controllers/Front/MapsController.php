<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class MapsController extends Controller
{
    public function index()
    {
        return view('front.maps.index');
    }
}
