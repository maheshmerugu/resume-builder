<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'currency',
        'interval',
        'period_days',
        'resume_limit',
        'download_limit',
        'edit_limit',
        'watermark',
        'features',
        'is_active',
        'is_featured',
        'is_default',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'watermark' => 'boolean',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_default' => 'boolean',
        ];
    }

    public const INTERVALS = [
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
        'lifetime' => 'Lifetime',
    ];

    /**
     * @return HasMany<Subscription>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function isFree(): bool
    {
        return $this->price <= 0;
    }

    public function isUnlimitedResumes(): bool
    {
        return is_null($this->resume_limit);
    }

    public function isUnlimitedDownloads(): bool
    {
        return is_null($this->download_limit);
    }

    public function priceLabel(): string
    {
        if ($this->isFree()) {
            return 'Free';
        }

        $symbol = $this->currency === 'INR' ? '₹' : $this->currency . ' ';

        return $symbol . number_format($this->price);
    }

    public function intervalLabel(): string
    {
        return match ($this->interval) {
            'monthly' => '/month',
            'yearly' => '/year',
            'lifetime' => ' one-time',
            default => '',
        };
    }

    /**
     * Number of days a subscription on this plan lasts. Null = never expires.
     */
    public function durationDays(): ?int
    {
        if ($this->interval === 'lifetime') {
            return null;
        }

        if ($this->period_days) {
            return $this->period_days;
        }

        return $this->interval === 'yearly' ? 365 : 30;
    }
}
