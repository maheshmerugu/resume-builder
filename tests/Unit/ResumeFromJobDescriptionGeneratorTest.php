<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\ResumeFromJobDescriptionGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResumeFromJobDescriptionGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_a_tailored_resume_from_job_description_locally(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $generator = app(ResumeFromJobDescriptionGenerator::class);

        $payload = $generator->generate($user, [
            'job_title' => 'Senior PHP Developer',
            'job_description' => 'We are hiring a Senior PHP Developer with Laravel, MySQL, REST API, and agile experience. Must have 4+ years building scalable backend services.',
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'current_role' => 'PHP Developer',
            'years_experience' => 5,
            'template' => 'modern',
        ]);

        $this->assertSame('Resume for Senior PHP Developer', $payload['title']);
        $this->assertSame('Test User', $payload['full_name']);
        $this->assertNotEmpty($payload['summary']);
        $this->assertNotEmpty($payload['skills']);
        $this->assertNotEmpty($payload['experience']);
        $this->assertStringContainsString('Senior PHP Developer', $payload['headline']);
    }
}
