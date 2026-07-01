<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->string('logo_path')->nullable();
            $table->text('description')->nullable();
            $table->string('whatsapp', 30)->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('website_url')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
