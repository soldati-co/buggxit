<?php

namespace Tests\Feature;

use App\Mail\AdminInvitationMail;
use App\Models\Admin;
use App\Models\AdminInvitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminInvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_send_an_invitation(): void
    {
        Mail::fake();
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.admins.invite'), ['email' => 'newadmin@example.com']);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('admin_invitations', [
            'email' => 'newadmin@example.com',
            'invited_by_admin_id' => $admin->id,
        ]);

        Mail::assertSent(AdminInvitationMail::class, function ($mail) {
            return $mail->email === 'newadmin@example.com';
        });
    }

    public function test_cannot_invite_an_email_that_is_already_an_admin(): void
    {
        Mail::fake();
        $admin = Admin::factory()->create();
        $existing = Admin::factory()->create(['email' => 'taken@example.com']);

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.admins.invite'), ['email' => 'taken@example.com']);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('admin_invitations', ['email' => 'taken@example.com']);
        Mail::assertNothingSent();
    }

    public function test_reinviting_the_same_pending_email_refreshes_the_existing_invitation(): void
    {
        Mail::fake();
        $admin = Admin::factory()->create();

        $this->actingAs($admin, 'admin')->post(route('admin.admins.invite'), ['email' => 'pending@example.com']);
        $firstToken = AdminInvitation::where('email', 'pending@example.com')->first()->token;

        $this->actingAs($admin, 'admin')->post(route('admin.admins.invite'), ['email' => 'pending@example.com']);

        $this->assertDatabaseCount('admin_invitations', 1);
        $second = AdminInvitation::where('email', 'pending@example.com')->first();
        $this->assertNotSame($firstToken, $second->token);
    }

    public function test_guest_cannot_send_an_invitation(): void
    {
        $response = $this->post(route('admin.admins.invite'), ['email' => 'newadmin@example.com']);

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_revoke_a_pending_invitation(): void
    {
        $admin = Admin::factory()->create();
        $invitation = AdminInvitation::create([
            'email' => 'revokeme@example.com',
            'token' => hash('sha256', 'whatever'),
            'invited_by_admin_id' => $admin->id,
            'expires_at' => now()->addDays(7),
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->delete(route('admin.admins.invitations.revoke', $invitation));

        $response->assertRedirect();
        $this->assertDatabaseMissing('admin_invitations', ['id' => $invitation->id]);
    }

    public function test_accept_form_shows_for_a_valid_token(): void
    {
        $plainToken = 'valid-token-123';
        AdminInvitation::create([
            'email' => 'invitee@example.com',
            'token' => hash('sha256', $plainToken),
            'expires_at' => now()->addDays(7),
        ]);

        $response = $this->get(route('admin.invitations.accept', [
            'token' => $plainToken,
            'email' => 'invitee@example.com',
        ]));

        $response->assertOk();
        $response->assertViewIs('admin.invitations.accept');
        $response->assertSee('invitee@example.com');
    }

    public function test_accept_form_rejects_an_invalid_token(): void
    {
        AdminInvitation::create([
            'email' => 'invitee@example.com',
            'token' => hash('sha256', 'correct-token'),
            'expires_at' => now()->addDays(7),
        ]);

        $response = $this->get(route('admin.invitations.accept', [
            'token' => 'wrong-token',
            'email' => 'invitee@example.com',
        ]));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_accept_form_rejects_an_expired_invitation(): void
    {
        $plainToken = 'valid-token-123';
        AdminInvitation::create([
            'email' => 'invitee@example.com',
            'token' => hash('sha256', $plainToken),
            'expires_at' => now()->subDay(),
        ]);

        $response = $this->get(route('admin.invitations.accept', [
            'token' => $plainToken,
            'email' => 'invitee@example.com',
        ]));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_accepting_a_valid_invitation_creates_an_admin_and_logs_them_in(): void
    {
        $plainToken = 'valid-token-123';
        $invitation = AdminInvitation::create([
            'email' => 'invitee@example.com',
            'token' => hash('sha256', $plainToken),
            'expires_at' => now()->addDays(7),
        ]);

        $response = $this->post(route('admin.invitations.store'), [
            'token' => $plainToken,
            'email' => 'invitee@example.com',
            'name' => 'Jane',
            'surname' => 'Doe',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $admin = Admin::where('email', 'invitee@example.com')->first();
        $this->assertNotNull($admin);
        $this->assertSame('Jane Doe', $admin->name);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password123', $admin->password));

        $this->assertTrue(Auth::guard('admin')->check());
        $this->assertSame($admin->id, Auth::guard('admin')->id());

        $this->assertNotNull($invitation->fresh()->accepted_at);
    }

    public function test_cannot_accept_the_same_invitation_twice(): void
    {
        $plainToken = 'valid-token-123';
        AdminInvitation::create([
            'email' => 'invitee@example.com',
            'token' => hash('sha256', $plainToken),
            'expires_at' => now()->addDays(7),
            'accepted_at' => now(),
        ]);

        $response = $this->post(route('admin.invitations.store'), [
            'token' => $plainToken,
            'email' => 'invitee@example.com',
            'name' => 'Jane',
            'surname' => 'Doe',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.login'));
        $this->assertDatabaseCount('admins', 0);
    }

    public function test_password_confirmation_is_required(): void
    {
        $plainToken = 'valid-token-123';
        AdminInvitation::create([
            'email' => 'invitee@example.com',
            'token' => hash('sha256', $plainToken),
            'expires_at' => now()->addDays(7),
        ]);

        $response = $this->post(route('admin.invitations.store'), [
            'token' => $plainToken,
            'email' => 'invitee@example.com',
            'name' => 'Jane',
            'surname' => 'Doe',
            'password' => 'password123',
            'password_confirmation' => 'mismatch',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseCount('admins', 0);
    }
}
