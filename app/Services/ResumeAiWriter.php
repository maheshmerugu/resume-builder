<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ResumeAiWriter
{
    /**
     * @param  array<string, mixed>  $context
     */
    public function generate(string $field, array $context): string
    {
        if ($this->isConfigured()) {
            try {
                return $this->generateWithProvider($field, $context);
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $this->generateLocally($field, $context);
    }

    public function isConfigured(): bool
    {
        return config('ai.provider') !== 'local' && filled(config('ai.api_key'));
    }

    public function providerLabel(): string
    {
        $provider = config('ai.provider', 'local');

        return config("ai.providers.{$provider}.label", ucfirst($provider));
    }

    public function modelLabel(): string
    {
        return $this->resolveModel();
    }

    public function signupUrl(): ?string
    {
        $provider = config('ai.provider', 'local');

        return config("ai.providers.{$provider}.signup_url");
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function generateWithProvider(string $field, array $context): string
    {
        $provider = config('ai.provider', 'openrouter');
        $providerConfig = config("ai.providers.{$provider}");

        if (! is_array($providerConfig) || empty($providerConfig['base_url'])) {
            throw new \RuntimeException("AI provider [{$provider}] is not configured.");
        }

        $apiKey = (string) config('ai.api_key');
        $errors = [];

        foreach ($this->modelsToTry() as $model) {
            try {
                $content = $this->requestChatCompletion(
                    baseUrl: $providerConfig['base_url'],
                    apiKey: $apiKey,
                    model: $model,
                    field: $field,
                    context: $context,
                    provider: $provider,
                );

                if ($content !== '') {
                    return $this->cleanOutput($field, $content);
                }
            } catch (\Throwable $e) {
                $errors[] = "{$model}: {$e->getMessage()}";
            }
        }

        throw new \RuntimeException($errors[0] ?? 'All AI models failed.');
    }

    /**
     * @return array<int, string>
     */
    protected function modelsToTry(): array
    {
        $provider = config('ai.provider', 'openrouter');
        $primary = $this->resolveModel();
        $fallbacks = config("ai.providers.{$provider}.fallback_models", []);

        return array_values(array_unique(array_filter([$primary, ...$fallbacks])));
    }

    protected function resolveModel(): string
    {
        if (filled(config('ai.model'))) {
            return (string) config('ai.model');
        }

        $provider = config('ai.provider', 'openrouter');

        return (string) config("ai.providers.{$provider}.default_model", 'google/gemma-2-9b-it:free');
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function requestChatCompletion(
        string $baseUrl,
        string $apiKey,
        string $model,
        string $field,
        array $context,
        string $provider,
    ): string {
        $request = Http::withToken($apiKey)
            ->timeout(config('ai.timeout', 45))
            ->acceptJson();

        if ($provider === 'openrouter') {
            $request = $request->withHeaders([
                'HTTP-Referer' => config('app.url', 'http://localhost'),
                'X-Title' => config('app.name', 'AI Resume Builder'),
            ]);
        }

        $response = $request->post($baseUrl, [
            'model' => $model,
            'temperature' => 0.7,
            'max_tokens' => $field === 'from_job_description' ? 2500 : config('ai.max_tokens', 800),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $field === 'from_job_description'
                        ? 'You are an expert resume writer. Create ATS-optimized resume content tailored to a specific job description. Return ONLY valid JSON with no markdown or commentary.'
                        : 'You are an expert resume writer for AI Resume Builder. Write concise, ATS-friendly, achievement-focused resume content. Use strong action verbs and measurable results where possible. Return only the requested content with no markdown, labels, or extra commentary.',
                ],
                [
                    'role' => 'user',
                    'content' => $this->buildPrompt($field, $context),
                ],
            ],
        ]);

        if (! $response->successful()) {
            $message = $response->json('error.message')
                ?? $response->json('error')
                ?? $response->body();

            throw new \RuntimeException(is_string($message) ? $message : 'AI request failed.');
        }

        $content = trim((string) $response->json('choices.0.message.content', ''));

        if ($content === '') {
            throw new \RuntimeException('AI returned empty content.');
        }

        return $content;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function buildPrompt(string $field, array $context): string
    {
        $name = $context['full_name'] ?? 'the candidate';
        $headline = $context['headline'] ?? '';
        $summary = $context['summary'] ?? '';
        $skills = $context['skills_raw'] ?? '';
        $experience = json_encode($context['experience'] ?? [], JSON_UNESCAPED_UNICODE);
        $projects = json_encode($context['projects'] ?? [], JSON_UNESCAPED_UNICODE);
        $education = json_encode($context['education'] ?? [], JSON_UNESCAPED_UNICODE);

        return match ($field) {
            'headline' => "Write a professional resume headline (max 12 words) for {$name}. Experience context: {$experience}. Return headline only.",
            'summary' => "Write a 3–4 sentence professional summary for {$name}. Headline: {$headline}. Experience: {$experience}. Skills: {$skills}. Return summary paragraph only.",
            'skills' => "Suggest 12–18 relevant technical and soft skills as a comma-separated list for {$name}. Headline: {$headline}. Experience: {$experience}. Return comma-separated skills only.",
            'experience_bullets' => $this->experiencePrompt($context),
            'project_description' => $this->projectPrompt($context),
            'languages' => "Suggest spoken languages for a professional resume for someone in {$context['location']}. Return comma-separated language names only (e.g. English, Hindi).",
            'full_resume' => "Improve empty resume sections for {$name}. Headline: {$headline}. Return JSON with keys: headline, summary, skills_raw (comma string), and experience bullets as array of strings for first job only. Experience: {$experience}. Education: {$education}.",
            'from_job_description' => $this->jobDescriptionPrompt($context),
            default => "Write professional resume content for field '{$field}' using context: headline={$headline}, experience={$experience}, projects={$projects}, summary={$summary}.",
        };
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function experiencePrompt(array $context): string
    {
        $index = (int) ($context['index'] ?? 0);
        $item = $context['experience'][$index] ?? [];
        $role = $item['role'] ?? 'Professional';
        $company = $item['company'] ?? 'the company';

        return "Write 4 resume bullet points (one per line, no bullets/numbers) for {$role} at {$company}. Use metrics and action verbs. Candidate headline: ".($context['headline'] ?? 'N/A').'. Return bullets only, one per line.';
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function projectPrompt(array $context): string
    {
        $index = (int) ($context['index'] ?? 0);
        $item = $context['projects'][$index] ?? [];
        $name = $item['name'] ?? 'Project';
        $tech = $item['tech'] ?? 'modern stack';

        return "Write a 2-sentence project description for resume project '{$name}' using {$tech}. Mention impact and your role. Return description only.";
    }

    protected function cleanOutput(string $field, string $content): string
    {
        $content = trim(preg_replace('/^```[\w]*\n?|```$/m', '', $content) ?? $content);

        if ($field === 'full_resume' || $field === 'from_job_description') {
            return $content;
        }

        return trim($content, "\"' \n\r\t");
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function generateLocally(string $field, array $context): string
    {
        return match ($field) {
            'headline' => $this->localHeadline($context),
            'summary' => $this->localSummary($context),
            'skills' => $this->localSkills($context),
            'experience_bullets' => $this->localExperienceBullets($context),
            'project_description' => $this->localProjectDescription($context),
            'languages' => $this->localLanguages($context),
            'full_resume' => json_encode($this->localFullResume($context), JSON_UNESCAPED_UNICODE),
            'from_job_description' => throw new \RuntimeException('Job description resume generation requires ResumeFromJobDescriptionGenerator.'),
            default => 'Professional content tailored to your experience and career goals.',
        };
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function localHeadline(array $context): string
    {
        $role = $this->primaryRole($context);

        return "{$role} | Building scalable solutions & delivering measurable impact";
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function localSummary(array $context): string
    {
        $name = $context['full_name'] ?? 'A motivated professional';
        $role = $this->primaryRole($context);
        $years = $this->estimateYears($context);
        $skills = $this->skillSample($context);

        return "{$name} is a results-driven {$role} with {$years}+ years of experience designing, building, and optimizing high-quality solutions. Skilled in {$skills}, with a track record of improving performance, collaborating across teams, and shipping reliable work on schedule. Known for clear communication, ownership, and translating business goals into practical deliverables.";
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function localSkills(array $context): string
    {
        $role = Str::lower($this->primaryRole($context));

        return match (true) {
            str_contains($role, 'data') || str_contains($role, 'analyst') => 'Python, SQL, Excel, Power BI, Data Visualization, Statistical Analysis, Pandas, Communication',
            str_contains($role, 'design') || str_contains($role, 'ui') || str_contains($role, 'ux') => 'Figma, Adobe XD, UI Design, UX Research, Prototyping, Design Systems, HTML, CSS',
            str_contains($role, 'market') || str_contains($role, 'sales') => 'SEO, Google Analytics, Content Strategy, CRM, Campaign Management, Copywriting, Lead Generation',
            str_contains($role, 'manager') || str_contains($role, 'lead') => 'Team Leadership, Stakeholder Management, Agile, Scrum, Roadmapping, Communication, Problem Solving',
            default => 'PHP, Laravel, JavaScript, React, MySQL, REST APIs, Git, Problem Solving, Communication, Team Collaboration',
        };
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function localExperienceBullets(array $context): string
    {
        $index = (int) ($context['index'] ?? 0);
        $item = $context['experience'][$index] ?? [];
        $role = $item['role'] ?? $this->primaryRole($context);
        $company = $item['company'] ?? 'the organization';

        $bullets = [
            "Led key initiatives as {$role} at {$company}, improving delivery speed and cross-team alignment across multiple projects.",
            'Designed and implemented solutions that increased efficiency, reduced manual effort, and improved overall product quality.',
            'Collaborated with stakeholders to gather requirements, prioritize work, and ship features that supported business goals.',
            'Mentored teammates, documented best practices, and contributed to a culture of continuous improvement and accountability.',
        ];

        return implode("\n", $bullets);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function localProjectDescription(array $context): string
    {
        $index = (int) ($context['index'] ?? 0);
        $item = $context['projects'][$index] ?? [];
        $name = $item['name'] ?? 'Portfolio Project';
        $tech = $item['tech'] ?? 'modern web technologies';

        return "Built {$name} using {$tech}, focusing on clean architecture, responsive design, and reliable performance. Delivered features end-to-end from planning through deployment while ensuring maintainable code and a smooth user experience.";
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function localLanguages(array $context): string
    {
        return 'English, Hindi, Telugu';
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function jobDescriptionPrompt(array $context): string
    {
        $name = $context['full_name'] ?? 'the candidate';
        $jobTitle = $context['job_title'] ?? 'the role';
        $jobDescription = Str::limit((string) ($context['job_description'] ?? ''), 6000, '');
        $background = Str::limit((string) ($context['background_notes'] ?? ''), 1500, '');
        $keywords = $context['jd_keywords'] ?? '';
        $source = json_encode($context['source_resume'] ?? [], JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
Create a complete tailored resume for {$name} targeting this job.

Target job title: {$jobTitle}
Key JD keywords to include naturally: {$keywords}
Candidate background notes: {$background}
Existing resume to adapt (optional): {$source}

Job description:
{$jobDescription}

Return ONLY valid JSON with these keys:
title, full_name, headline, email, phone, location, summary,
skills (array of strings),
experience (array of objects with role, company, location, start, end, bullets as newline string),
education (array with degree, school, field, start, end),
projects (array with name, tech, description),
certifications (array with name, issuer, year),
languages (array of strings)

Use realistic but generic employer names if unknown. Bullets must include metrics and JD keywords. Keep content concise and ATS-friendly.
PROMPT;
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    protected function localFullResume(array $context): array
    {
        return [
            'headline' => $this->localHeadline($context),
            'summary' => $this->localSummary($context),
            'skills_raw' => $this->localSkills($context),
            'experience_bullets' => explode("\n", $this->localExperienceBullets(array_merge($context, ['index' => 0]))),
        ];
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function primaryRole(array $context): string
    {
        if (filled($context['headline'] ?? null)) {
            return Str::before((string) $context['headline'], '|') ?: (string) $context['headline'];
        }

        $first = $context['experience'][0]['role'] ?? null;
        if (filled($first)) {
            return (string) $first;
        }

        return 'Professional';
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function estimateYears(array $context): int
    {
        $count = count(array_filter((array) ($context['experience'] ?? []), fn ($e) => filled($e['role'] ?? null) || filled($e['company'] ?? null)));

        return max(2, min(10, $count * 2 + 2));
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function skillSample(array $context): string
    {
        $skills = collect(preg_split('/[,\n]+/', (string) ($context['skills_raw'] ?? '')) ?: [])
            ->map(fn ($s) => trim($s))
            ->filter()
            ->take(4)
            ->implode(', ');

        return $skills ?: 'modern tools, agile delivery, and cross-functional collaboration';
    }
}
