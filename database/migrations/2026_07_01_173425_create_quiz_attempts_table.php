<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_candidate_id')->unique()->constrained()->cascadeOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('expires_at');
            $table->enum('status', ['in_progress', 'submitted', 'graded', 'flagged'])->default('in_progress');
            $table->decimal('score', 5, 2)->nullable();
            $table->unsignedInteger('rank')->nullable();
            $table->json('question_order')->nullable();
            $table->json('anti_cheat_flags')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
