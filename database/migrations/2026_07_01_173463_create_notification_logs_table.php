<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('channel', ['mail', 'whatsapp', 'sms', 'fcm', 'database']);
            $table->string('subject', 255)->nullable();
            $table->enum('status', ['queued', 'sent', 'failed'])->default('queued');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['channel', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
