<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'template',
        'full_name',
        'headline',
        'email',
        'phone',
        'location',
        'linkedin',
        'website',
        'summary',
        'experience',
        'education',
        'skills',
        'projects',
        'certifications',
        'languages',
    ];

    protected function casts(): array
    {
        return [
            'experience' => 'array',
            'education' => 'array',
            'skills' => 'array',
            'projects' => 'array',
            'certifications' => 'array',
            'languages' => 'array',
        ];
    }

    /**
     * Templates available for rendering.
     *
     * @var array<string, string>
     */
    public const TEMPLATES = [
        'modern' => 'Modern',
        'classic' => 'Classic',
        'minimal' => 'Minimal',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<AtsCheck>
     */
    public function atsChecks(): HasMany
    {
        return $this->hasMany(AtsCheck::class)->latest();
    }

    /**
     * Flatten all textual content of the resume into a single searchable string.
     * Used by the ATS scorer to match against a job description.
     */
    public function toPlainText(): string
    {
        $parts = [
            $this->full_name,
            $this->headline,
            $this->summary,
        ];

        foreach (['experience', 'projects'] as $section) {
            foreach ((array) $this->{$section} as $item) {
                $parts[] = implode(' ', array_filter([
                    $item['title'] ?? null,
                    $item['role'] ?? null,
                    $item['company'] ?? null,
                    $item['name'] ?? null,
                    $item['description'] ?? null,
                    is_array($item['bullets'] ?? null) ? implode(' ', $item['bullets']) : ($item['bullets'] ?? null),
                    is_array($item['tech'] ?? null) ? implode(' ', $item['tech']) : ($item['tech'] ?? null),
                ]));
            }
        }

        foreach ((array) $this->education as $item) {
            $parts[] = implode(' ', array_filter([
                $item['degree'] ?? null,
                $item['school'] ?? null,
                $item['field'] ?? null,
            ]));
        }

        foreach ((array) $this->skills as $skill) {
            $parts[] = is_array($skill) ? implode(' ', $skill) : $skill;
        }

        foreach ((array) $this->certifications as $cert) {
            $parts[] = is_array($cert) ? ($cert['name'] ?? '') : $cert;
        }

        return strtolower(trim(implode(' ', array_filter($parts))));
    }

    public function completeness(): int
    {
        $checks = [
            filled($this->full_name),
            filled($this->email),
            filled($this->summary),
            ! empty($this->experience),
            ! empty($this->education),
            ! empty($this->skills),
        ];

        return (int) round((count(array_filter($checks)) / count($checks)) * 100);
    }
}
