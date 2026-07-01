<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('institution', 150)->nullable();
            $table->string('class_level', 50)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('arrondissement', 100)->nullable();
            $table->string('parent_name', 150)->nullable();
            $table->string('parent_phone', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
