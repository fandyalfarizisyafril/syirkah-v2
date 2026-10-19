<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_media', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200)->nullable();
            $table->text('description')->nullable();
            $table->string('source', 20);
            $table->string('file_path')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->string('youtube_id', 11)->nullable();
            $table->string('poster_path')->nullable();
            $table->string('poster_mime_type', 100)->nullable();
            $table->unsignedInteger('width')->default(16);
            $table->unsignedInteger('height')->default(9);
            $table->string('status', 20)->default('draft')->index();
            $table->unsignedInteger('sort_order')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_media');
    }
};
