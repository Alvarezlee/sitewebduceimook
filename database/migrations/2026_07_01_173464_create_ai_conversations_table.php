<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('session_id', 100)->nullable();
            $table->enum('channel', ['assistant', 'quiz_generation'])->default('assistant');
            $table->timestamps();

            $table->index('session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_conversations');
    }
};
