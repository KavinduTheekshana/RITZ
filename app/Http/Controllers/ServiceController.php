<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::where('status', 1)->get();
        $services = Service::where('status', 1)
            ->orderBy('order', 'asc')
            ->get();
        return view('frontend.services.index', compact('services', 'testimonials'));
    }

    public function show($slug)
    {
        // Change 'active()' to 'where('status', true)'
        $service = Service::where('slug', $slug)->where('status', true)->firstOrFail();

        $otherServices = Service::where('id', '!=', $service->id)
            ->where('status', true)
            ->orderBy('order')
            ->take(5)
            ->get();

        // Combine current service with others
        $allServices = collect([$service])->concat($otherServices)->take(6);

        return view('frontend.services.show', compact('service', 'allServices'));
    }
}
