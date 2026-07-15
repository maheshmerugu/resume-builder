<?php

namespace Database\Seeders;

use App\Models\AtsCheck;
use App\Models\Plan;
use App\Models\Resume;
use App\Models\User;
use App\Services\AtsScorer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPlans();

        $user = User::updateOrCreate(
            ['email' => 'demo@resumeforge.test'],
            ['name' => 'Demo User', 'password' => Hash::make('password')]
        );

        User::updateOrCreate(
            ['email' => 'admin@resumeforge.test'],
            ['name' => 'Admin', 'password' => Hash::make('password'), 'is_admin' => true]
        );

        $resume = Resume::updateOrCreate(
            ['user_id' => $user->id, 'title' => 'Full Stack Developer Resume'],
            [
                'template' => 'modern',
                'full_name' => 'Mahesh Merugu',
                'headline' => 'Full Stack Developer | PHP · Laravel · React · MySQL',
                'email' => 'demo@resumeforge.test',
                'phone' => '+91 90000 00000',
                'location' => 'Hyderabad, India',
                'linkedin' => 'linkedin.com/in/example',
                'website' => 'github.com/example',
                'summary' => 'Full Stack Developer with 5+ years of experience building scalable web applications using PHP, Laravel, MySQL, and REST APIs, with hands-on React.js front-end development. Skilled in database design, query optimization, and delivering end-to-end features in agile teams.',
                'experience' => [
                    [
                        'role' => 'Senior Software Engineer',
                        'company' => 'EduNXT',
                        'location' => 'Hyderabad',
                        'start' => 'Nov 2024',
                        'end' => 'Present',
                        'bullets' => "Built ERP modules with Laravel, MySQL, and REST APIs used by 5000+ users\nDeveloped React.js dashboards and reusable UI components\nOptimized MySQL queries reducing report load time by 40%",
                    ],
                    [
                        'role' => 'Software Engineer',
                        'company' => 'VMAX E-Solutions',
                        'location' => 'Hyderabad',
                        'start' => 'Sep 2021',
                        'end' => 'Oct 2023',
                        'bullets' => "Delivered healthcare and LMS platforms using Laravel and REST APIs\nImplemented RBAC and secure data handling\nIntegrated React.js front-end with backend services",
                    ],
                ],
                'education' => [
                    ['degree' => 'MCA', 'field' => 'Computer Applications', 'school' => 'GVP College of Engineering', 'start' => '2015', 'end' => '2018'],
                ],
                'skills' => ['PHP', 'Laravel', 'MySQL', 'React.js', 'JavaScript', 'REST APIs', 'Git', 'HTML5', 'CSS3', 'Query Optimization'],
                'projects' => [
                    ['name' => 'College ERP', 'tech' => 'Laravel, MySQL, React.js', 'description' => 'Multi-module ERP for hostel, transport, academic, and finance operations.'],
                ],
                'certifications' => [
                    ['name' => 'AWS Certified Cloud Practitioner', 'issuer' => 'Amazon', 'year' => '2024'],
                ],
                'languages' => ['English', 'Telugu', 'Hindi'],
            ]
        );

        $jd = 'We are looking for a Full Stack Developer with strong experience in PHP, Laravel, MySQL and REST APIs. '
            . 'React.js front-end experience is required. Must have strong SQL and query optimization skills, Git version control, '
            . 'and experience building scalable web applications. AWS knowledge is a plus. Docker and Kubernetes experience preferred.';

        $result = app(AtsScorer::class)->score($resume, $jd);

        AtsCheck::updateOrCreate(
            ['user_id' => $user->id, 'resume_id' => $resume->id, 'job_title' => 'Full Stack Developer'],
            [
                'job_description' => $jd,
                'score' => $result['score'],
                'matched_keywords' => $result['matched'],
                'missing_keywords' => $result['missing'],
                'suggestions' => $result['suggestions'],
            ]
        );

        $this->command->info("Seeded demo user (demo@resumeforge.test / password) with ATS score: {$result['score']}");
    }

    protected function seedPlans(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Get started and try things out.',
                'price' => 0,
                'interval' => 'monthly',
                'resume_limit' => 1,
                'download_limit' => 1,
                'edit_limit' => null,
                'watermark' => true,
                'features' => [
                    '1 resume',
                    '1 PDF download / month',
                    'ATS score checker',
                    'Watermarked PDF',
                ],
                'is_active' => true,
                'is_featured' => false,
                'is_default' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Perfect for an active job search.',
                'price' => 150,
                'interval' => 'monthly',
                'resume_limit' => 3,
                'download_limit' => null,
                'edit_limit' => null,
                'watermark' => false,
                'features' => [
                    '3 resumes',
                    'Unlimited edits',
                    'Unlimited PDF downloads',
                    'No watermark',
                    'All templates',
                    'Unlimited ATS checks',
                ],
                'is_active' => true,
                'is_featured' => true,
                'is_default' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'For professionals managing many versions.',
                'price' => 299,
                'interval' => 'monthly',
                'resume_limit' => 15,
                'download_limit' => null,
                'edit_limit' => null,
                'watermark' => false,
                'features' => [
                    '15 resumes',
                    'Unlimited edits & downloads',
                    'No watermark',
                    'All templates',
                    'Priority ATS suggestions',
                ],
                'is_active' => true,
                'is_featured' => false,
                'is_default' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Lifetime',
                'slug' => 'lifetime',
                'description' => 'Pay once, use forever.',
                'price' => 1999,
                'interval' => 'lifetime',
                'resume_limit' => null,
                'download_limit' => null,
                'edit_limit' => null,
                'watermark' => false,
                'features' => [
                    'Unlimited resumes',
                    'Unlimited edits & downloads',
                    'No watermark',
                    'All templates',
                    'One-time payment',
                ],
                'is_active' => true,
                'is_featured' => false,
                'is_default' => false,
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }

        $this->command->info('Seeded ' . count($plans) . ' subscription plans.');
    }
}
