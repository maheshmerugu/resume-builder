<?php

namespace App\Support;

class ResumeThemes
{
    protected static ?array $cache = null;

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        if (self::$cache === null) {
            self::$cache = config('resume-themes.themes', []);
        }

        return self::$cache;
    }

    /**
     * @return array<string, mixed>
     */
    public static function get(string $id): array
    {
        return self::all()[self::resolve($id)] ?? self::all()['modern'];
    }

    public static function resolve(string $id): string
    {
        if (isset(self::all()[$id])) {
            return $id;
        }

        return 'modern';
    }

    public static function exists(string $id): bool
    {
        return isset(self::all()[$id]);
    }

    /**
     * @return array<int, string>
     */
    public static function ids(): array
    {
        return array_keys(self::all());
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        return collect(self::all())->mapWithKeys(fn ($theme, $id) => [$id => $theme['label']])->all();
    }

    /**
     * @return array<int, string>
     */
    public static function categories(): array
    {
        return collect(self::all())
            ->pluck('category')
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function featured(int $limit = 6): array
    {
        return collect(self::all())
            ->filter(fn ($theme) => $theme['featured'] ?? false)
            ->take($limit)
            ->all();
    }

    /**
     * Condensed catalog for Alpine.js live preview.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function catalog(): array
    {
        return collect(self::all())->map(fn ($theme) => [
            'label' => $theme['label'],
            'layout' => $theme['layout'],
            'font' => $theme['font'],
            'category' => $theme['category'],
            'tagline' => $theme['tagline'],
            'accent' => $theme['accent'],
            'colors' => array_merge($theme['colors'], [
                'primary_dark' => $theme['colors']['primary_dark'] ?? $theme['colors']['primary'],
            ]),
        ])->all();
    }

    public static function count(): int
    {
        return count(self::all());
    }
}
