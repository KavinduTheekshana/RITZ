<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Partner;
use App\Models\Service;
use App\Models\Team;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::where('status', 1)
            ->orderBy('order', 'asc')
            ->limit(4)
            ->get();
        $partners = Partner::where('status', 1)
            ->get();
        $testimonials = Testimonial::where('status', 1)->get();
        $faqs = Faq::where('status', 1)->get();

        return view('frontend.home.index', compact('services', 'partners', 'testimonials', 'faqs'));
    }

    public function contact()
    {
        return view('frontend.contact.index');
    }

    public function about()
    {
        $partners = Partner::where('status', 1)
            ->get();
        $team = Team::where('status', 1)
            ->get();
        return view('frontend.about.index', compact('partners', 'team'));
    }
}
