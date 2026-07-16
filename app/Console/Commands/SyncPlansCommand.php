<?php

namespace App\Console\Commands;

use Database\Seeders\PlanSeeder;
use Illuminate\Console\Command;

class SyncPlansCommand extends Command
{
    protected $signature = 'plans:sync';

    protected $description = 'Create or update default subscription plans';

    public function handle(): int
    {
        PlanSeeder::sync();

        $this->info('Default subscription plans synced.');

        return self::SUCCESS;
    }
}
