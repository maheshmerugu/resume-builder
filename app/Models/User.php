<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'is_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * @return HasMany<Resume>
     */
    public function resumes(): HasMany
    {
        return $this->hasMany(Resume::class)->latest();
    }

    /**
     * @return HasMany<AtsCheck>
     */
    public function atsChecks(): HasMany
    {
        return $this->hasMany(AtsCheck::class)->latest();
    }

    /**
     * @return HasMany<Subscription>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class)->latest();
    }

    /**
     * @return HasOne<Subscription>
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->latestOfMany();
    }

    public function billingEnabled(): bool
    {
        return (bool) config('billing.enabled', true);
    }

    /**
     * Whether the user may use paid features (create resumes, download PDFs, AI, etc.).
     */
    public function hasPlanAccess(): bool
    {
        if (! $this->billingEnabled()) {
            return true;
        }

        return $this->currentPlan() !== null;
    }

    /**
     * The plan the user is currently on (active subscription only).
     */
    public function currentPlan(): ?Plan
    {
        $subscription = $this->activeSubscription;

        if ($subscription && $subscription->plan) {
            return $subscription->plan;
        }

        return null;
    }

    public function resumeLimit(): ?int
    {
        return $this->currentPlan()?->resume_limit;
    }

    public function downloadLimit(): ?int
    {
        return $this->currentPlan()?->download_limit;
    }

    public function downloadsUsed(): int
    {
        return (int) ($this->activeSubscription?->downloads_used ?? 0);
    }

    public function canCreateResume(): bool
    {
        if (! $this->billingEnabled()) {
            return true;
        }

        if (! $this->currentPlan()) {
            return false;
        }

        $limit = $this->resumeLimit();

        if (is_null($limit)) {
            return true;
        }

        return $this->resumes()->count() < $limit;
    }

    public function remainingResumes(): ?int
    {
        $limit = $this->resumeLimit();

        if (is_null($limit)) {
            return null;
        }

        return max(0, $limit - $this->resumes()->count());
    }

    public function canDownload(): bool
    {
        if (! $this->billingEnabled()) {
            return true;
        }

        if (! $this->currentPlan()) {
            return false;
        }

        $limit = $this->downloadLimit();

        if (is_null($limit)) {
            return true;
        }

        return $this->downloadsUsed() < $limit;
    }

    public function remainingDownloads(): ?int
    {
        $limit = $this->downloadLimit();

        if (is_null($limit)) {
            return null;
        }

        return max(0, $limit - $this->downloadsUsed());
    }

    /**
     * Record a PDF download against the active subscription (for metered plans).
     */
    public function recordDownload(): void
    {
        $this->activeSubscription?->increment('downloads_used');
    }
}
