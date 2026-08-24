<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminInvitationMail;
use App\Models\Admin;
use App\Models\AdminInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('name')->get();

        $invitations = AdminInvitation::whereNull('accepted_at')
            ->with('invitedBy')
            ->latest()
            ->get();

        return view('admin.admins.index', compact('admins', 'invitations'));
    }

    public function invite(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        if (Admin::where('email', $validated['email'])->exists()) {
            return back()->withErrors(['email' => 'This email is already registered as an admin.'])->withInput();
        }

        $plainToken = Str::random(64);

        // Re-inviting an email with an already-pending invitation refreshes
        // that same invitation (new token, new expiry) rather than piling up
        // duplicate rows.
        $invitation = AdminInvitation::updateOrCreate(
            ['email' => $validated['email'], 'accepted_at' => null],
            [
                'token' => hash('sha256', $plainToken),
                'invited_by_admin_id' => Auth::guard('admin')->id(),
                'expires_at' => now()->addDays(7),
            ]
        );

        Mail::to($invitation->email)->send(new AdminInvitationMail(
            email: $invitation->email,
            token: $plainToken,
            inviterName: Auth::guard('admin')->user()?->name,
        ));

        return back()->with('success', "Invitation sent to {$invitation->email}.");
    }

    public function revoke(AdminInvitation $invitation)
    {
        if ($invitation->isAccepted()) {
            return back()->with('error', 'This invitation has already been accepted.');
        }

        $invitation->delete();

        return back()->with('success', 'Invitation revoked.');
    }
}
