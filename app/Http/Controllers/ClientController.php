<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class ClientController extends Controller
{
    public function profile() {
        return view('backend.profile.index');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $client = Auth::guard('client')->user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $client->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        // Update the password
        $client->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully!');
    }
}