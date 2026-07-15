<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AtsCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resume_id',
        'job_title',
        'job_description',
        'score',
        'matched_keywords',
        'missing_keywords',
        'suggestions',
    ];

    protected function casts(): array
    {
        return [
            'matched_keywords' => 'array',
            'missing_keywords' => 'array',
            'suggestions' => 'array',
            'score' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }

    public function scoreLabel(): string
    {
        return match (true) {
            $this->score >= 80 => 'Excellent match',
            $this->score >= 60 => 'Good match',
            $this->score >= 40 => 'Needs improvement',
            default => 'Poor match',
        };
    }

    public function scoreColor(): string
    {
        return match (true) {
            $this->score >= 80 => 'green',
            $this->score >= 60 => 'blue',
            $this->score >= 40 => 'yellow',
            default => 'red',
        };
    }
}
