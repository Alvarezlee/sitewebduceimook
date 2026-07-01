<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_conversation_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['user', 'assistant', 'system']);
            $table->longText('content');
            $table->unsignedInteger('tokens_used')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_messages');
    }
};
