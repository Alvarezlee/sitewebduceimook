<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_subject_id')->constrained()->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('source', ['ai', 'teacher'])->default('teacher');
            $table->text('question');
            $table->text('explanation')->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->boolean('is_open_ended')->default(false);
            $table->boolean('is_validated')->default(false);
            $table->timestamps();

            $table->index(['quiz_subject_id', 'is_validated']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
