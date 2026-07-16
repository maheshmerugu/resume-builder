<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Blog
{
    protected static ?Collection $posts = null;

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function published(): Collection
    {
        if (self::$posts !== null) {
            return self::$posts;
        }

        $directory = base_path('content/blog');

        if (! is_dir($directory)) {
            return self::$posts = collect();
        }

        self::$posts = collect(File::files($directory))
            ->map(fn ($file) => self::parse($file->getPathname()))
            ->filter(fn ($post) => $post !== null && ($post['published'] ?? true))
            ->sortByDesc('published_at')
            ->values();

        return self::$posts;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function find(string $slug): ?array
    {
        return self::published()->firstWhere('slug', $slug);
    }

    /**
     * @return array<string, mixed>|null
     */
    protected static function parse(string $path): ?array
    {
        $raw = File::get($path);

        if (! preg_match('/^---\s*\R(.*?)\R---\s*\R(.*)\z/s', $raw, $matches)) {
            return null;
        }

        $meta = self::parseFrontMatter($matches[1]);
        $body = trim($matches[2]);

        if (empty($meta['title']) || empty($meta['slug'])) {
            return null;
        }

        $publishedAt = $meta['published_at'] ?? now()->toDateString();

        return [
            'title' => (string) $meta['title'],
            'slug' => (string) $meta['slug'],
            'description' => (string) ($meta['description'] ?? ''),
            'keywords' => (string) ($meta['keywords'] ?? ''),
            'author' => (string) ($meta['author'] ?? config('seo.site_name')),
            'published_at' => $publishedAt,
            'updated_at' => (string) ($meta['updated_at'] ?? $publishedAt),
            'published' => (bool) ($meta['published'] ?? true),
            'reading_minutes' => max(1, (int) ceil(str_word_count(strip_tags($body)) / 200)),
            'body' => $body,
            'html' => Str::markdown($body),
            'url' => route('blog.show', ['slug' => $meta['slug']], absolute: true),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected static function parseFrontMatter(string $yaml): array
    {
        $meta = [];

        foreach (preg_split('/\R/', $yaml) as $line) {
            if (! str_contains($line, ':')) {
                continue;
            }

            [$key, $value] = array_map('trim', explode(':', $line, 2));
            $meta[$key] = trim($value, " \t\"'");
        }

        return $meta;
    }
}
