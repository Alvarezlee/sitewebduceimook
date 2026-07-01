<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 170)->unique();
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('library_categories')->nullOnDelete();
            $table->string('icon', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_categories');
    }
};
