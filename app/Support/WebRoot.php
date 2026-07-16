<?php

namespace App\Support;

class WebRoot
{
    /**
     * URL prefix for static files when the web root is the project folder (not public/).
     * Set by root index.php via LARAVEL_WEB_PUBLIC_PREFIX.
     */
    public static function publicPrefix(): string
    {
        if (defined('LARAVEL_WEB_PUBLIC_PREFIX')) {
            return LARAVEL_WEB_PUBLIC_PREFIX;
        }

        $configured = env('PUBLIC_WEB_PREFIX');

        return is_string($configured) ? $configured : '';
    }

    public static function assetOrigin(): ?string
    {
        $prefix = self::publicPrefix();

        if ($prefix === '') {
            return null;
        }

        if ($custom = env('ASSET_URL')) {
            return rtrim($custom, '/');
        }

        return rtrim((string) config('app.url'), '/').$prefix;
    }
}
