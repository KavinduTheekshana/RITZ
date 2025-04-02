<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('status', 1)
            ->orderBy('order', 'asc')
            ->limit(6)
            ->get();
            $partners = Partner::where('status', 1)
            ->get();
        return view('frontend.home.index', compact('services', 'partners'));
    }
}
