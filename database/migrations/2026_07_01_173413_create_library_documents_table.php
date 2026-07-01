<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_category_id')->constrained()->restrictOnDelete();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->enum('type', [
                'probatoire', 'baccalaureat', 'bepc', 'cap', 'bts',
                'concours', 'corrige', 'fascicule', 'livre',
            ]);
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('cover_path')->nullable();
            $table->unsignedInteger('file_size')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->boolean('is_free')->default(false);
            $table->unsignedInteger('downloads_count')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('library_documents');
    }
};
