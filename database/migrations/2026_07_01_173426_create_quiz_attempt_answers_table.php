<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_question_id')->constrained()->restrictOnDelete();
            $table->foreignId('quiz_question_option_id')->nullable()->constrained()->nullOnDelete();
            $table->text('open_answer_text')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->decimal('points_awarded', 5, 2)->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();

            $table->unique(['quiz_attempt_id', 'quiz_question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempt_answers');
    }
};
