<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('payable');
            $table->string('gateway', 50)->default('monetbil');
            $table->string('gateway_reference', 150)->nullable()->unique();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('XAF');
            $table->string('phone_number', 30)->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'cancelled', 'refunded'])->default('pending');
            $table->json('payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
