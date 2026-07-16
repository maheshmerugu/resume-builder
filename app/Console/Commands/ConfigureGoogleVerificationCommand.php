<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConfigureGoogleVerificationCommand extends Command
{
    protected $signature = 'seo:google-verification {code : Google Search Console HTML tag content value}';

    protected $description = 'Save Google Search Console verification code to .env';

    public function handle(): int
    {
        $code = trim($this->argument('code'));

        if ($code === '' || strlen($code) < 20) {
            $this->error('Invalid verification code. Copy the content value from the HTML meta tag in Search Console.');

            return self::FAILURE;
        }

        $envPath = base_path('.env');

        if (! is_file($envPath)) {
            $this->error('.env file not found. Copy .env.example to .env first.');

            return self::FAILURE;
        }

        $env = file_get_contents($envPath);

        if ($env === false) {
            $this->error('Could not read .env file.');

            return self::FAILURE;
        }

        if (preg_match('/^GOOGLE_SITE_VERIFICATION=.*$/m', $env)) {
            $env = preg_replace('/^GOOGLE_SITE_VERIFICATION=.*$/m', 'GOOGLE_SITE_VERIFICATION='.$code, $env);
        } else {
            $env = rtrim($env)."\n\nGOOGLE_SITE_VERIFICATION={$code}\n";
        }

        file_put_contents($envPath, $env);

        $this->callSilent('config:clear');

        $this->info('Google verification code saved to .env');
        $this->line('Meta tag will appear on your homepage after deploy.');
        $this->line('Sitemap URL: '.route('sitemap', absolute: true));

        return self::SUCCESS;
    }
}
