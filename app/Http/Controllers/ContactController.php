<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'subject'   => 'required|string|max:255',
            'message'   => 'required|string|min:10',
            'newsletter'=> 'nullable|boolean',
        ]);

        // Send email to your company address (configured in .env)
        Mail::to(config('mail.from.address'))->send(new ContactMail($validated));

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent. We\'ll respond within 24-48 hours.'
        ]);
    }
}