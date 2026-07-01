<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_albums', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->enum('type', ['photo', 'video']);
            $table->string('cover_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_albums');
    }
};
