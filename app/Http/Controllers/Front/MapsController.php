<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MapsController extends Controller
{
    public function index(): View
    {
        return view('front.maps.index');
    }
}
