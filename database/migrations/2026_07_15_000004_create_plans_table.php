<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();

            $table->unsignedInteger('price')->default(0);         // in rupees
            $table->string('currency', 8)->default('INR');
            $table->string('interval')->default('monthly');        // monthly | yearly | lifetime
            $table->unsignedInteger('period_days')->nullable();    // null = lifetime / no expiry

            $table->unsignedInteger('resume_limit')->nullable();   // null = unlimited
            $table->unsignedInteger('download_limit')->nullable(); // null = unlimited (per period)
            $table->unsignedInteger('edit_limit')->nullable();     // null = unlimited
            $table->boolean('watermark')->default(false);

            $table->json('features')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_default')->default(false);         // fallback free tier
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
