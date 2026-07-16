<?php

use Database\Seeders\PlanSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('plans')) {
            return;
        }

        PlanSeeder::sync();
    }

    public function down(): void
    {
        // Plans may be referenced by subscriptions; leave data in place on rollback.
    }
};
