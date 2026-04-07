<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdminResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(config('app.url') . route('admin.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $name = $notifiable->name ?? 'Admin';

        return (new MailMessage)
            ->subject('Reset Your Admin Password – BUGGXIT Couture')
            ->view('vendor.notifications.email', [  
                'resetUrl' => $resetUrl,
                'name' => $name,
            ]);
    }
}