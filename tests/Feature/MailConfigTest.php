<?php

namespace Tests\Feature;

use Tests\TestCase;

class MailConfigTest extends TestCase
{
    public function test_resend_mailer_is_configured(): void
    {
        $this->assertArrayHasKey('resend', config('mail.mailers'));
        $this->assertSame('resend', config('mail.mailers.resend.transport'));
        $this->assertArrayHasKey('key', config('services.resend'));
    }

    public function test_testing_environment_uses_array_mailer(): void
    {
        $this->assertSame('array', config('mail.default'));
    }
}
