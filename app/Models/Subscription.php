<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'starts_at',
        'ends_at',
        'downloads_used',
        'amount_paid',
        'payment_reference',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        return is_null($this->ends_at) || $this->ends_at->isFuture();
    }

    public function isExpired(): bool
    {
        return ! is_null($this->ends_at) && $this->ends_at->isPast();
    }

    public function daysLeft(): ?int
    {
        if (is_null($this->ends_at)) {
            return null;
        }

        return max(0, (int) now()->diffInDays($this->ends_at, false));
    }
}
