<?php

namespace App\Notifications;

use App\Models\Plan;
use App\Support\MailBranding;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionConfirmedNotification extends Notification
{

    public function __construct(
        public Plan $plan,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $appName = MailBranding::appName();

        return (new MailMessage)
            ->subject("Your {$this->plan->name} plan is active — {$appName}")
            ->greeting("Hi {$notifiable->name},")
            ->line("Payment received. You are now on the {$this->plan->name} plan ({$this->plan->priceLabel()}{$this->plan->intervalLabel()}).")
            ->line('You can create resumes, run ATS checks, and download PDFs based on your plan limits.')
            ->action('View your dashboard', route('dashboard'))
            ->line('Questions about billing? Contact '.MailBranding::supportEmail().'.');
    }
}
