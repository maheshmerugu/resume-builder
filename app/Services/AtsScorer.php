<?php

namespace App\Services;

use App\Models\Resume;

class AtsScorer
{
    /**
     * Common English + resume filler words to ignore when extracting keywords.
     *
     * @var array<int, string>
     */
    protected array $stopWords = [
        'the', 'and', 'for', 'are', 'but', 'not', 'you', 'all', 'any', 'can', 'her', 'was', 'one', 'our', 'out',
        'day', 'get', 'has', 'him', 'his', 'how', 'man', 'new', 'now', 'old', 'see', 'two', 'way', 'who', 'boy',
        'did', 'its', 'let', 'put', 'say', 'she', 'too', 'use', 'with', 'this', 'that', 'have', 'from', 'they',
        'will', 'your', 'what', 'when', 'were', 'been', 'their', 'would', 'there', 'which', 'about', 'into', 'them',
        'than', 'then', 'some', 'such', 'more', 'most', 'other', 'work', 'working', 'experience', 'years', 'year',
        'role', 'team', 'teams', 'ability', 'strong', 'good', 'excellent', 'preferred', 'required', 'requirements',
        'responsibilities', 'skills', 'skill', 'knowledge', 'plus', 'must', 'should', 'looking', 'candidate',
        'join', 'help', 'across', 'within', 'using', 'related', 'etc', 'including', 'well', 'also', 'per', 'via',
        'job', 'description', 'company', 'position', 'opportunity', 'environment', 'business', 'proficient',
        'understanding', 'familiarity', 'hands', 'design', 'develop', 'development', 'build', 'building', 'support',
    ];

    /**
     * Multi-word technical phrases worth detecting as single keywords.
     *
     * @var array<int, string>
     */
    protected array $phrases = [
        'rest api', 'rest apis', 'restful api', 'api gateway', 'ci/cd', 'ci cd', 'unit testing', 'version control',
        'machine learning', 'data structures', 'step functions', 'secrets manager', 'cloudwatch events',
        'problem solving', 'full stack', 'front end', 'back end', 'material ui', 'node js', 'next js', 'react js',
        'spring boot', 'sql server', 'micro services', 'microservices', 'object oriented', 'test driven',
    ];

    /**
     * Score a resume against a job description.
     *
     * @return array{score:int, matched:array<int,string>, missing:array<int,string>, suggestions:array<int,string>}
     */
    public function score(Resume $resume, string $jobDescription): array
    {
        $resumeText = $resume->toPlainText();
        $keywords = $this->extractKeywords($jobDescription);

        $matched = [];
        $missing = [];

        foreach ($keywords as $keyword) {
            if ($this->textContains($resumeText, $keyword)) {
                $matched[] = $keyword;
            } else {
                $missing[] = $keyword;
            }
        }

        $total = count($keywords);
        $keywordScore = $total > 0 ? (count($matched) / $total) * 100 : 0;

        // Blend keyword match with structural completeness for a fairer ATS score.
        $score = (int) round(($keywordScore * 0.8) + ($resume->completeness() * 0.2));
        $score = max(0, min(100, $score));

        return [
            'score' => $score,
            'matched' => array_values(array_slice($matched, 0, 40)),
            'missing' => array_values(array_slice($missing, 0, 40)),
            'suggestions' => $this->buildSuggestions($resume, $missing, $score),
        ];
    }

    /**
     * @return array<int, string>
     */
    public function extractKeywords(string $text): array
    {
        $text = strtolower($text);
        $keywords = [];

        foreach ($this->phrases as $phrase) {
            if (str_contains($text, $phrase)) {
                $keywords[$phrase] = true;
            }
        }

        // Tokenize: keep letters, numbers, +, #, . (for c++, c#, node.js)
        $clean = preg_replace('/[^a-z0-9+#.\s]/', ' ', $text);
        $tokens = preg_split('/\s+/', (string) $clean, -1, PREG_SPLIT_NO_EMPTY);

        $counts = [];
        foreach ($tokens as $token) {
            $token = trim($token, '.');
            if (mb_strlen($token) < 3 || is_numeric($token)) {
                continue;
            }
            if (in_array($token, $this->stopWords, true)) {
                continue;
            }
            $counts[$token] = ($counts[$token] ?? 0) + 1;
        }

        arsort($counts);
        foreach (array_keys($counts) as $token) {
            $keywords[$token] = true;
        }

        return array_slice(array_keys($keywords), 0, 30);
    }

    protected function textContains(string $haystack, string $needle): bool
    {
        // Word-boundary aware match, tolerant of special chars like c++ / c#.
        $escaped = preg_quote($needle, '/');
        $pattern = '/(?<![a-z0-9])' . $escaped . '(?![a-z0-9])/i';

        return (bool) preg_match($pattern, $haystack);
    }

    /**
     * @param  array<int, string>  $missing
     * @return array<int, string>
     */
    protected function buildSuggestions(Resume $resume, array $missing, int $score): array
    {
        $suggestions = [];

        if ($score < 60 && ! empty($missing)) {
            $top = implode(', ', array_slice($missing, 0, 8));
            $suggestions[] = "Add these missing keywords from the job description where truthful: {$top}.";
        }

        if (blank($resume->summary)) {
            $suggestions[] = 'Add a professional summary at the top with your years of experience and core stack — ATS and recruiters both scan it first.';
        } elseif (str_word_count((string) $resume->summary) < 30) {
            $suggestions[] = 'Expand your summary to 40–60 words and include role-specific keywords.';
        }

        if (empty($resume->skills)) {
            $suggestions[] = 'Add a dedicated Skills section — ATS systems weight an explicit skills list heavily.';
        }

        if (empty($resume->experience)) {
            $suggestions[] = 'Add work experience with measurable, keyword-rich bullet points (start each with an action verb).';
        }

        if ($score >= 80) {
            $suggestions[] = 'Strong match. Tailor your top 3 bullets to mirror the exact wording of the job description for an even higher score.';
        }

        $suggestions[] = 'Use standard section headings (Experience, Education, Skills) and avoid tables or images so ATS parsers read your resume correctly.';

        return $suggestions;
    }
}
