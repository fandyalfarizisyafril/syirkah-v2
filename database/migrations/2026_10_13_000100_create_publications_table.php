<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('category', 30);
            $table->string('file_path');
            $table->string('original_filename');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size');
            $table->string('thumbnail_path')->nullable();
            $table->string('thumbnail_mime_type', 100)->nullable();
            $table->string('status', 20)->default('draft');
            $table->boolean('allow_download')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_demo')->default(false);
            $table->string('demo_key')->nullable()->unique();
            $table->timestamps();
            $table->index(['status', 'category', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
