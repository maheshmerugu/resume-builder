<?php

namespace App\Notifications;

use App\Support\MailBranding;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
{

    public function __construct(
        public string $context = 'profile',
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appName = MailBranding::appName();

        $message = (new MailMessage)
            ->subject("Your {$appName} password was changed")
            ->greeting("Hi {$notifiable->name},");

        if ($this->context === 'reset') {
            $message->line('Your password was reset successfully using the forgot-password link.');
        } else {
            $message->line('Your account password was updated from your profile settings.');
        }

        return $message
            ->line('If you made this change, no further action is required.')
            ->line('If you did not change your password, contact us immediately at '.MailBranding::supportEmail().'.');
    }
}
