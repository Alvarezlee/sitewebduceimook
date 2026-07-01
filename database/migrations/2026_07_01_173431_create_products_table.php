<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_category_id')->constrained()->restrictOnDelete();
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->enum('type', ['livre', 'fascicule', 'cours', 'pdf', 'logiciel', 'formation', 'abonnement']);
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('compare_at_price', 12, 2)->nullable();
            $table->unsignedInteger('stock')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('file_path')->nullable();
            $table->boolean('is_digital')->default(true);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
