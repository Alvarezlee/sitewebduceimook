<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('library_document_id')->constrained()->cascadeOnDelete();
            $table->foreignId('library_subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('downloaded_at')->useCurrent();

            $table->index(['user_id', 'downloaded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_downloads');
    }
};
