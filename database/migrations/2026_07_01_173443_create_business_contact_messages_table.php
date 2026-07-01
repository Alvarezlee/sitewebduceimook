<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_contact_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('email', 190);
            $table->string('phone', 30)->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_contact_messages');
    }
};
