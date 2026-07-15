<?php

namespace App\Support;

use App\Models\Resume;

class ResumeCompleteness
{
    /**
     * @return array{
     *     percent: int,
     *     checks: list<array{key: string, label: string, done: bool, hint: string, section: string}>,
     *     next: ?array{key: string, label: string, hint: string, section: string},
     *     label: string,
     *     status: string,
     *     tier: string
     * }
     */
    public static function for(Resume $resume): array
    {
        $checks = self::checksFromData([
            'full_name' => $resume->full_name,
            'email' => $resume->email,
            'summary' => $resume->summary,
            'experience' => (array) $resume->experience,
            'education' => (array) $resume->education,
            'skills' => (array) $resume->skills,
        ]);

        return self::buildResult($checks);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{percent: int, checks: list<array<string, mixed>>, next: ?array<string, mixed>, label: string, status: string, tier: string}
     */
    public static function fromFormData(array $data): array
    {
        $skillsRaw = $data['skills_raw'] ?? '';
        $skills = is_array($skillsRaw)
            ? $skillsRaw
            : array_filter(array_map('trim', preg_split('/[,\n]+/', (string) $skillsRaw) ?: []));

        $checks = self::checksFromData([
            'full_name' => $data['full_name'] ?? null,
            'email' => $data['email'] ?? null,
            'summary' => $data['summary'] ?? null,
            'experience' => (array) ($data['experience'] ?? []),
            'education' => (array) ($data['education'] ?? []),
            'skills' => $skills,
        ]);

        return self::buildResult($checks);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return list<array{key: string, label: string, done: bool, hint: string, section: string}>
     */
    protected static function checksFromData(array $data): array
    {
        return [
            [
                'key' => 'name',
                'label' => 'Name',
                'done' => filled($data['full_name'] ?? null),
                'hint' => 'Add your full name in Contact details.',
                'section' => 'section-contact',
            ],
            [
                'key' => 'email',
                'label' => 'Email',
                'done' => filled($data['email'] ?? null),
                'hint' => 'Add a professional email address.',
                'section' => 'section-contact',
            ],
            [
                'key' => 'summary',
                'label' => 'Summary',
                'done' => filled($data['summary'] ?? null),
                'hint' => 'Write a short professional summary.',
                'section' => 'section-summary',
            ],
            [
                'key' => 'experience',
                'label' => 'Experience',
                'done' => self::hasRepeaterContent((array) ($data['experience'] ?? []), ['role', 'company']),
                'hint' => 'Add at least one role with company name.',
                'section' => 'section-experience',
            ],
            [
                'key' => 'education',
                'label' => 'Education',
                'done' => self::hasRepeaterContent((array) ($data['education'] ?? []), ['degree', 'school']),
                'hint' => 'Add your degree and school.',
                'section' => 'section-education',
            ],
            [
                'key' => 'skills',
                'label' => 'Skills',
                'done' => ! empty((array) ($data['skills'] ?? [])),
                'hint' => 'List your top technical or professional skills.',
                'section' => 'section-skills',
            ],
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $rows
     * @param  list<string>  $fields
     */
    protected static function hasRepeaterContent(array $rows, array $fields): bool
    {
        return collect($rows)->contains(function ($item) use ($fields) {
            if (! is_array($item)) {
                return false;
            }

            return collect($fields)->contains(fn ($field) => filled($item[$field] ?? null));
        });
    }

    /**
     * @param  list<array{key: string, label: string, done: bool, hint: string, section: string}>  $checks
     * @return array{percent: int, checks: list<array<string, mixed>>, next: ?array<string, mixed>, label: string, status: string, tier: string}
     */
    protected static function buildResult(array $checks): array
    {
        $done = count(array_filter($checks, fn ($check) => $check['done']));
        $percent = (int) round(($done / max(count($checks), 1)) * 100);
        $next = collect($checks)->first(fn ($check) => ! $check['done']);

        return [
            'percent' => $percent,
            'checks' => $checks,
            'next' => $next ?: null,
            'label' => self::labelFor($percent),
            'status' => self::statusFor($percent),
            'tier' => self::tierFor($percent),
        ];
    }

    public static function labelFor(int $percent): string
    {
        if ($percent >= 100) {
            return 'Your resume is ready to save and download.';
        }

        if ($percent >= 67) {
            return 'Almost there — fill in the remaining sections.';
        }

        if ($percent >= 34) {
            return 'Good progress — keep going.';
        }

        return 'Just getting started — complete the key sections below.';
    }

    public static function statusFor(int $percent): string
    {
        if ($percent >= 100) {
            return 'Complete';
        }

        if ($percent >= 67) {
            return 'Almost done';
        }

        if ($percent >= 34) {
            return 'In progress';
        }

        return 'Getting started';
    }

    public static function tierFor(int $percent): string
    {
        if ($percent >= 100) {
            return 'complete';
        }

        if ($percent >= 67) {
            return 'high';
        }

        if ($percent >= 34) {
            return 'mid';
        }

        return 'low';
    }

    public static function barClass(int $percent): string
    {
        return match (self::tierFor($percent)) {
            'complete' => 'bg-gradient-to-r from-emerald-500 to-teal-500',
            'high' => 'bg-gradient-to-r from-indigo-500 to-violet-500',
            'mid' => 'bg-gradient-to-r from-blue-500 to-cyan-500',
            default => 'bg-gradient-to-r from-amber-400 to-orange-500',
        };
    }

    public static function textClass(int $percent): string
    {
        return match (self::tierFor($percent)) {
            'complete' => 'text-emerald-600 dark:text-emerald-400',
            'high' => 'text-indigo-600 dark:text-indigo-400',
            'mid' => 'text-blue-600 dark:text-blue-400',
            default => 'text-amber-600 dark:text-amber-400',
        };
    }

    public static function ringClass(int $percent): string
    {
        return match (self::tierFor($percent)) {
            'complete' => 'stroke-emerald-500',
            'high' => 'stroke-indigo-500',
            'mid' => 'stroke-blue-500',
            default => 'stroke-amber-500',
        };
    }

    public static function badgeClass(int $percent): string
    {
        return match (self::tierFor($percent)) {
            'complete' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300',
            'high' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300',
            'mid' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-300',
            default => 'bg-amber-50 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300',
        };
    }
}
