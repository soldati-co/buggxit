<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use App\Notifications\AdminResetPasswordNotification;


class Admin extends Authenticatable implements CanResetPassword
{
    use Notifiable, CanResetPasswordTrait;

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }
    
    protected $guard = 'admin';

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // ← CRITICAL for Laravel 8+ auto-hashing
    ];

    /**
     * Get the guard that should be used for the model.
     */
    public function guardName(): string
    {
        return 'admin';
    }

    
}