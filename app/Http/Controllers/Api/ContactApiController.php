<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class ContactApiController extends Controller
{
    public function submit(ContactRequest $request)
    {
        Mail::to(config('mail.store_notification_address'))->send(new ContactMail($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent. We will respond within 24-48 hours.',
        ]);
    }
}
