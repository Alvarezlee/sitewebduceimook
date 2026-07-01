<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_editions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 170)->unique();
            $table->decimal('registration_price', 10, 2);
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->unsignedInteger('duration_minutes');
            $table->unsignedTinyInteger('max_attempts')->default(1);
            $table->unsignedInteger('questions_per_attempt')->default(20);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_editions');
    }
};
