<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_edition_id')->constrained()->restrictOnDelete();
            $table->string('photo_path')->nullable();
            $table->string('institution', 150)->nullable();
            $table->string('class_level', 50)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('arrondissement', 100)->nullable();
            $table->string('parent_name', 150)->nullable();
            $table->string('parent_phone', 30)->nullable();
            $table->enum('registration_status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->unique(['user_id', 'quiz_edition_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_candidates');
    }
};
