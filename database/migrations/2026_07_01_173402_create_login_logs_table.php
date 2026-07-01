<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email_attempted', 190)->nullable();
            $table->string('ip_address', 45);
            $table->string('user_agent', 255)->nullable();
            $table->enum('status', ['success', 'failed']);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['ip_address', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
};
