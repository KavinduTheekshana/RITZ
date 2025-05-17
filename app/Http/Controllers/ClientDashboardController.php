<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index()
    {
        // $client = Auth::user(); 
        $client = Auth::guard('client')->user();

        $companies = $client->companies; // M:M relationship
        $selfAssessment = $client->selfAssessment; // 1:1 relationship
        return view('backend.dashboard.index', compact('companies','selfAssessment'));
    }
}
