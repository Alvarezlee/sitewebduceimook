<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('library_plan_id')->constrained()->restrictOnDelete();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->unsignedInteger('downloads_used')->default(0);
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_subscriptions');
    }
};
