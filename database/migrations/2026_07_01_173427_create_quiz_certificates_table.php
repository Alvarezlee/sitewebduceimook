<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('certificate_number', 100)->unique();
            $table->string('file_path');
            $table->timestamp('issued_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_certificates');
    }
};
