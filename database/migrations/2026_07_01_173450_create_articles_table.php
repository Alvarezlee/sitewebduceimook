<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('category', 100)->nullable();
            $table->string('title', 220);
            $table->string('slug', 240)->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('content');
            $table->string('cover_path')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(false);
            $table->string('seo_title', 220)->nullable();
            $table->string('seo_description', 320)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_published', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
