<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $email,
        public string $token,
        public ?string $inviterName = null,
    ) {
    }

    public function build()
    {
        $acceptUrl = route('admin.invitations.accept', [
            'token' => $this->token,
            'email' => $this->email,
        ]);

        return $this->subject('You\'ve been invited to BUGGXIT Couture Admin')
            ->view('emails.admin-invitation', [
                'acceptUrl' => $acceptUrl,
                'inviterName' => $this->inviterName,
            ]);
    }
}
