<?php

namespace App\Services;

use App\Models\Resume;
use App\Models\User;
use App\Support\ResumeThemes;
use Illuminate\Support\Str;

class ResumeFromJobDescriptionGenerator
{
    public function __construct(
        protected ResumeAiWriter $writer,
        protected AtsScorer $scorer,
    ) {}

    /**
     * Build a complete resume payload from a job description and candidate context.
     *
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function generate(User $user, array $input): array
    {
        $context = $this->buildContext($user, $input);

        if ($this->writer->isConfigured()) {
            try {
                $raw = $this->writer->generate('from_job_description', $context);
                $parsed = $this->parseGeneratedPayload($raw);

                if ($parsed !== null) {
                    return $this->normalizePayload($parsed, $user, $input);
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $this->generateLocally($user, $input, $context);
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    protected function buildContext(User $user, array $input): array
    {
        $jobDescription = (string) ($input['job_description'] ?? '');
        $keywords = $this->scorer->extractKeywords($jobDescription);

        $context = [
            'full_name' => $input['full_name'] ?? $user->name,
            'email' => $input['email'] ?? $user->email,
            'phone' => $input['phone'] ?? '',
            'location' => $input['location'] ?? '',
            'job_title' => $input['job_title'] ?? '',
            'job_description' => $jobDescription,
            'background_notes' => $input['background_notes'] ?? '',
            'years_experience' => $input['years_experience'] ?? '',
            'current_role' => $input['current_role'] ?? '',
            'jd_keywords' => implode(', ', array_slice($keywords, 0, 25)),
        ];

        if (! empty($input['source_resume_id'])) {
            $source = $user->resumes()->find($input['source_resume_id']);
            if ($source instanceof Resume) {
                $context['source_resume'] = [
                    'headline' => $source->headline,
                    'summary' => $source->summary,
                    'experience' => $source->experience,
                    'education' => $source->education,
                    'skills' => $source->skills,
                    'projects' => $source->projects,
                ];
            }
        }

        return $context;
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function parseGeneratedPayload(string $raw): ?array
    {
        $raw = trim(preg_replace('/^```(?:json)?\n?|```$/m', '', $raw) ?? $raw);

        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        $start = strpos($raw, '{');
        $end = strrpos($raw, '}');
        if ($start !== false && $end !== false && $end > $start) {
            $decoded = json_decode(substr($raw, $start, $end - $start + 1), true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    protected function normalizePayload(array $payload, User $user, array $input): array
    {
        $jobTitle = trim((string) ($input['job_title'] ?? ''));
        $title = trim((string) ($payload['title'] ?? ''));
        if ($title === '') {
            $title = $jobTitle !== '' ? "Resume for {$jobTitle}" : 'Tailored Resume';
        }

        $skills = $payload['skills'] ?? $payload['skills_raw'] ?? [];
        if (is_string($skills)) {
            $skills = preg_split('/[,\n]+/', $skills) ?: [];
        }

        $languages = $payload['languages'] ?? [];
        if (is_string($languages)) {
            $languages = preg_split('/[,\n]+/', $languages) ?: [];
        }

        return [
            'title' => Str::limit($title, 120, ''),
            'template' => ResumeThemes::resolve($input['template'] ?? 'modern'),
            'full_name' => trim((string) ($payload['full_name'] ?? $input['full_name'] ?? $user->name)),
            'headline' => Str::limit(trim((string) ($payload['headline'] ?? '')), 160, ''),
            'email' => trim((string) ($payload['email'] ?? $input['email'] ?? $user->email)),
            'phone' => Str::limit(trim((string) ($payload['phone'] ?? $input['phone'] ?? '')), 60, ''),
            'location' => Str::limit(trim((string) ($payload['location'] ?? $input['location'] ?? '')), 120, ''),
            'linkedin' => Str::limit(trim((string) ($payload['linkedin'] ?? '')), 200, ''),
            'website' => Str::limit(trim((string) ($payload['website'] ?? '')), 200, ''),
            'summary' => Str::limit(trim((string) ($payload['summary'] ?? '')), 2000, ''),
            'experience' => $this->normalizeExperience($payload['experience'] ?? []),
            'education' => $this->normalizeEducation($payload['education'] ?? []),
            'projects' => $this->normalizeProjects($payload['projects'] ?? []),
            'certifications' => $this->normalizeCertifications($payload['certifications'] ?? []),
            'skills' => collect($skills)->map(fn ($s) => trim((string) $s))->filter()->unique()->values()->all(),
            'languages' => collect($languages)->map(fn ($s) => trim((string) $s))->filter()->unique()->values()->all(),
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    protected function generateLocally(User $user, array $input, array $context): array
    {
        $jobTitle = trim((string) ($input['job_title'] ?? ''));
        if ($jobTitle === '') {
            $jobTitle = $this->inferJobTitle((string) ($input['job_description'] ?? ''));
        }

        $keywords = $this->scorer->extractKeywords((string) ($input['job_description'] ?? ''));
        $skills = array_slice(array_values(array_unique(array_merge(
            array_slice($keywords, 0, 12),
            $this->defaultSkillsForRole($jobTitle),
        ))), 0, 18);

        $years = max(2, min(12, (int) ($input['years_experience'] ?: 4)));
        $currentRole = trim((string) ($input['current_role'] ?? $jobTitle));
        $name = trim((string) ($input['full_name'] ?? $user->name));
        $background = trim((string) ($input['background_notes'] ?? ''));

        $headline = "{$jobTitle} | ".implode(' · ', array_slice($skills, 0, 3));
        $summary = "{$name} is an experienced {$currentRole} with {$years}+ years delivering measurable results in fast-paced environments. "
            .'Skilled in '.implode(', ', array_slice($skills, 0, 6))
            .'. Proven ability to align work with business goals, collaborate across teams, and ship high-quality outcomes. '
            .($background !== '' ? Str::limit($background, 220, '.').'. ' : '')
            ."Tailored for roles matching: {$jobTitle}.";

        $experience = [[
            'role' => $currentRole,
            'company' => 'Previous Company',
            'location' => trim((string) ($input['location'] ?? '')),
            'start' => (string) (now()->year - $years),
            'end' => 'Present',
            'bullets' => implode("\n", [
                'Delivered key initiatives aligned with '.$jobTitle.' requirements, improving efficiency and stakeholder satisfaction.',
                'Applied '.($skills[0] ?? 'modern tools').' and '.($skills[1] ?? 'best practices').' to solve complex problems and ship reliable solutions.',
                'Partnered with cross-functional teams to prioritize work, reduce delivery risk, and improve measurable outcomes.',
                'Contributed to process improvements that increased productivity and supported scalable growth.',
            ]),
        ]];

        if (! empty($context['source_resume']['experience']) && is_array($context['source_resume']['experience'])) {
            $experience = $this->normalizeExperience($context['source_resume']['experience']);
            if (isset($experience[0])) {
                $experience[0]['bullets'] = $this->tailorBullets(
                    (string) ($experience[0]['bullets'] ?? ''),
                    $jobTitle,
                    $skills,
                );
            }
        }

        $education = [[
            'degree' => 'Bachelor\'s Degree',
            'school' => 'University',
            'field' => $this->fieldForRole($jobTitle),
            'start' => (string) (now()->year - $years - 4),
            'end' => (string) (now()->year - $years),
        ]];

        if (! empty($context['source_resume']['education']) && is_array($context['source_resume']['education'])) {
            $education = $this->normalizeEducation($context['source_resume']['education']);
        }

        return [
            'title' => "Resume for {$jobTitle}",
            'template' => ResumeThemes::resolve($input['template'] ?? 'modern'),
            'full_name' => $name,
            'headline' => Str::limit($headline, 160, ''),
            'email' => trim((string) ($input['email'] ?? $user->email)),
            'phone' => Str::limit(trim((string) ($input['phone'] ?? '')), 60, ''),
            'location' => Str::limit(trim((string) ($input['location'] ?? '')), 120, ''),
            'linkedin' => '',
            'website' => '',
            'summary' => Str::limit($summary, 2000, ''),
            'experience' => $experience,
            'education' => $education,
            'projects' => [],
            'certifications' => [],
            'skills' => $skills,
            'languages' => ['English', 'Hindi'],
        ];
    }

    protected function inferJobTitle(string $jobDescription): string
    {
        if (preg_match('/(?:position|role|title|hiring)[:\s-]+([^\n\.]{4,80})/i', $jobDescription, $matches)) {
            return trim($matches[1]);
        }

        $keywords = $this->scorer->extractKeywords($jobDescription);

        return Str::title(implode(' ', array_slice($keywords, 0, 3))) ?: 'Professional Role';
    }

    /**
     * @param  array<int, mixed>  $rows
     * @return array<int, array<string, mixed>>
     */
    protected function normalizeExperience(array $rows): array
    {
        $clean = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $role = trim((string) ($row['role'] ?? $row['title'] ?? ''));
            $company = trim((string) ($row['company'] ?? ''));
            if ($role === '' && $company === '') {
                continue;
            }

            $bullets = $row['bullets'] ?? '';
            if (is_array($bullets)) {
                $bullets = implode("\n", array_map('strval', $bullets));
            }

            $clean[] = [
                'role' => Str::limit($role, 160, ''),
                'company' => Str::limit($company, 160, ''),
                'location' => Str::limit(trim((string) ($row['location'] ?? '')), 120, ''),
                'start' => Str::limit(trim((string) ($row['start'] ?? '')), 40, ''),
                'end' => Str::limit(trim((string) ($row['end'] ?? '')), 40, ''),
                'bullets' => Str::limit(trim((string) $bullets), 3000, ''),
            ];
        }

        return array_values($clean);
    }

    /**
     * @param  array<int, mixed>  $rows
     * @return array<int, array<string, mixed>>
     */
    protected function normalizeEducation(array $rows): array
    {
        $clean = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $degree = trim((string) ($row['degree'] ?? ''));
            $school = trim((string) ($row['school'] ?? ''));
            if ($degree === '' && $school === '') {
                continue;
            }

            $clean[] = [
                'degree' => Str::limit($degree, 160, ''),
                'school' => Str::limit($school, 160, ''),
                'field' => Str::limit(trim((string) ($row['field'] ?? '')), 160, ''),
                'start' => Str::limit(trim((string) ($row['start'] ?? '')), 40, ''),
                'end' => Str::limit(trim((string) ($row['end'] ?? '')), 40, ''),
            ];
        }

        return array_values($clean);
    }

    /**
     * @param  array<int, mixed>  $rows
     * @return array<int, array<string, mixed>>
     */
    protected function normalizeProjects(array $rows): array
    {
        $clean = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $clean[] = [
                'name' => Str::limit($name, 160, ''),
                'tech' => Str::limit(trim((string) ($row['tech'] ?? '')), 200, ''),
                'description' => Str::limit(trim((string) ($row['description'] ?? '')), 1000, ''),
            ];
        }

        return array_values($clean);
    }

    /**
     * @param  array<int, mixed>  $rows
     * @return array<int, array<string, mixed>>
     */
    protected function normalizeCertifications(array $rows): array
    {
        $clean = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $clean[] = [
                'name' => Str::limit($name, 200, ''),
                'issuer' => Str::limit(trim((string) ($row['issuer'] ?? '')), 160, ''),
                'year' => Str::limit(trim((string) ($row['year'] ?? '')), 20, ''),
            ];
        }

        return array_values($clean);
    }

    /**
     * @param  array<int, string>  $skills
     */
    protected function tailorBullets(string $existing, string $jobTitle, array $skills): string
    {
        $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $existing) ?: [])));

        if ($lines === []) {
            return implode("\n", [
                "Optimized experience for {$jobTitle} using ".($skills[0] ?? 'relevant skills').' and '.($skills[1] ?? 'industry best practices').'.',
                'Delivered measurable results while collaborating with cross-functional stakeholders.',
            ]);
        }

        $lines[0] = 'Tailored for '.$jobTitle.': '.$lines[0];

        return Str::limit(implode("\n", $lines), 3000, '');
    }

    /**
     * @return array<int, string>
     */
    protected function defaultSkillsForRole(string $jobTitle): array
    {
        $role = Str::lower($jobTitle);

        return match (true) {
            str_contains($role, 'data') || str_contains($role, 'analyst') => ['Python', 'SQL', 'Excel', 'Power BI', 'Statistics'],
            str_contains($role, 'design') || str_contains($role, 'ui') => ['Figma', 'UI Design', 'UX Research', 'Prototyping', 'Design Systems'],
            str_contains($role, 'market') => ['SEO', 'Google Analytics', 'Content Strategy', 'Campaign Management'],
            str_contains($role, 'manager') || str_contains($role, 'lead') => ['Leadership', 'Stakeholder Management', 'Agile', 'Roadmapping'],
            default => ['Communication', 'Problem Solving', 'Team Collaboration', 'Project Delivery'],
        };
    }

    protected function fieldForRole(string $jobTitle): string
    {
        $role = Str::lower($jobTitle);

        return match (true) {
            str_contains($role, 'engineer') || str_contains($role, 'developer') => 'Computer Science',
            str_contains($role, 'design') => 'Design',
            str_contains($role, 'market') => 'Marketing',
            str_contains($role, 'data') => 'Data Science',
            default => 'Business Administration',
        };
    }
}
