<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminInvitationController extends Controller
{
    /**
     * Show the "create your admin account" form for a valid invitation link.
     */
    public function accept(Request $request, string $token)
    {
        $invitation = $this->findValidInvitation($request->query('email'), $token);

        if (! $invitation) {
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'This invitation link is invalid or has expired.']);
        }

        return view('admin.invitations.accept', [
            'token' => $token,
            'email' => $invitation->email,
        ]);
    }

    /**
     * Create the admin account from a valid invitation and log them in.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $invitation = $this->findValidInvitation($validated['email'], $validated['token']);

        if (! $invitation) {
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'This invitation link is invalid or has expired.']);
        }

        if (Admin::where('email', $invitation->email)->exists()) {
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'This email is already registered as an admin. Please log in.']);
        }

        $admin = Admin::create([
            'name' => trim($validated['name'].' '.$validated['surname']),
            'email' => $invitation->email,
            'password' => $validated['password'],
        ]);

        $invitation->update(['accepted_at' => now()]);

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('success', "Welcome to BUGGXIT admin, {$admin->name}!");
    }

    /**
     * Look up a still-open invitation for the given email and verify the
     * plaintext token against its stored hash. Never trusts the token alone
     * -- the email must also match a real, unaccepted, unexpired row.
     */
    private function findValidInvitation(?string $email, string $token): ?AdminInvitation
    {
        if (! $email) {
            return null;
        }

        $invitation = AdminInvitation::where('email', $email)
            ->whereNull('accepted_at')
            ->latest()
            ->first();

        if (! $invitation || $invitation->isExpired()) {
            return null;
        }

        if (! hash_equals($invitation->token, hash('sha256', $token))) {
            return null;
        }

        return $invitation;
    }
}
