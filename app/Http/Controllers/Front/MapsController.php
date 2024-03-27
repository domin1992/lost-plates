<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class MapsController extends Controller
{
    public function index(string $lang): View
    {
        return view('front.maps.index');
    }
}
