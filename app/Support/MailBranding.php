<?php

namespace App\Support;

class MailBranding
{
    public static function appName(): string
    {
        return (string) config('seo.site_name', config('app.name', 'AI Resume Builder'));
    }

    public static function supportEmail(): string
    {
        return (string) config('seo.contact_email', config('mail.from.address'));
    }
}
