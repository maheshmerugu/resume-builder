<?php

namespace App\Listeners;

use App\Notifications\PasswordChangedNotification;
use Illuminate\Auth\Events\PasswordReset;

class SendPasswordChangedEmail
{
    public function handle(PasswordReset $event): void
    {
        $event->user->notify(new PasswordChangedNotification(context: 'reset'));
    }
}
