<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('specialty', 150)->nullable();
            $table->string('institution', 150)->nullable();
            $table->text('bio')->nullable();
            $table->string('bureau_role', 100)->nullable();
            $table->unsignedSmallInteger('bureau_order')->nullable();
            $table->boolean('is_bureau_member')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_profiles');
    }
};
