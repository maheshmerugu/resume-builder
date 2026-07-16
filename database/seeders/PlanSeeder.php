<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        self::sync();

        if ($this->command) {
            $this->command->info('Seeded '.count(self::definitions()).' subscription plans.');
        }
    }

    public static function sync(): void
    {
        foreach (self::definitions() as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }

        Plan::where('slug', 'free')->delete();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function definitions(): array
    {
        return [
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
                'sort_order' => 1,
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
                'sort_order' => 2,
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
                'sort_order' => 3,
            ],
        ];
    }
}
