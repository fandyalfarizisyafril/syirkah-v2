<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('none');
        });

        foreach (['categories', 'brands', 'industries'] as $name) {
            Schema::create($name, function (Blueprint $table) use ($name) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->string('image_alt')->nullable();
                $table->string('status')->default('draft')->index();
                $table->unsignedInteger('sort_order')->default(0);
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                if ($name === 'brands') {
                    $table->string('focus')->nullable();
                    $table->string('website_url')->nullable();
                }
                if ($name === 'industries') {
                    $table->text('challenges')->nullable();
                    $table->text('solution_copy')->nullable();
                }
                $table->timestamps();
            });
        }

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('brand_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('model_or_series')->nullable();
            $table->text('short_description');
            $table->text('description')->nullable();
            $table->json('benefits')->nullable();
            $table->json('applications')->nullable();
            $table->json('specifications')->nullable();
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->json('gallery')->nullable();
            $table->string('datasheet_file')->nullable();
            $table->boolean('featured')->default(false);
            $table->string('status')->default('draft')->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });

        Schema::create('industry_product', function (Blueprint $table) {
            $table->foreignId('industry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->primary(['industry_id', 'product_id']);
        });

        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('company');
            $table->string('email');
            $table->string('phone');
            $table->text('application')->nullable();
            $table->text('message');
            $table->string('source_page')->nullable();
            $table->string('status')->default('new')->index();
            $table->text('internal_notes')->nullable();
            $table->timestamp('consent_at');
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->json('data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['settings', 'inquiries', 'industry_product', 'products', 'industries', 'brands', 'categories'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('role'));
    }
};
