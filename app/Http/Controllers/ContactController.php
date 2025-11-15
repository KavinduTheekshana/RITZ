<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string|max:2000',
            'cf-turnstile-response' => 'required|turnstile',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Save to DB or send email here
        Contact::create($request->only('name', 'email', 'message'));

        // Return a success response
        return response()->json([
            'status' => 'success',
            'message' => 'Thank you for your message. We will get back to you soon!',
        ], 201);
    }
}
