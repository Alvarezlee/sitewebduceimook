<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 220);
            $table->string('slug', 240)->unique();
            $table->text('description')->nullable();
            $table->string('location', 255)->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->string('cover_path')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
