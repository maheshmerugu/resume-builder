<?php

namespace App\Notifications;

use App\Support\MailBranding;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appName = MailBranding::appName();

        return (new MailMessage)
            ->subject("Welcome to {$appName}")
            ->greeting("Hi {$notifiable->name},")
            ->line("Thanks for signing up at {$appName}. Your account is ready.")
            ->line('You can paste any job description and generate a tailored ATS resume in seconds, run ATS checks, and download print-ready PDFs.')
            ->action('Go to your dashboard', route('dashboard'))
            ->line('Need help? Reply to this email or contact us at '.MailBranding::supportEmail().'.');
    }
}
