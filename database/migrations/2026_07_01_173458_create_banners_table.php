<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200)->nullable();
            $table->string('image_path');
            $table->string('link_url')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->enum('position', ['home_hero', 'home_carousel', 'shop'])->default('home_hero');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['position', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
