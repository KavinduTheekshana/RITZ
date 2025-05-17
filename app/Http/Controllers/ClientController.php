<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function profile() {
        return view('backend.profile.index');
    }
}
