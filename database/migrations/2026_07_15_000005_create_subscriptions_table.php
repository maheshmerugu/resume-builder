<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();

            $table->string('status')->default('active');   // active | expired | cancelled
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();       // null = lifetime

            $table->unsignedInteger('downloads_used')->default(0);
            $table->unsignedInteger('amount_paid')->default(0);
            $table->string('payment_reference')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
