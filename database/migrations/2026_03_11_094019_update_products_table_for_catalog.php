<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // Catalog relationships
            $table->foreignId('brand_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->after('brand_id')
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('attribute_family_id')
                ->nullable()
                ->after('category_id')
                ->constrained()
                ->nullOnDelete();

            // SEO / URLs
            $table->string('slug')
                ->unique()
                ->nullable()
                ->after('name');

            // Pricing improvements
            $table->decimal('special_price', 10, 2)
                ->nullable()
                ->after('price');

            // Product status
            $table->boolean('is_active')
                ->default(true)
                ->after('stock');

            $table->boolean('is_featured')
                ->default(false)
                ->after('is_active');

            // Media
            $table->string('image')
                ->nullable()
                ->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            $table->dropColumn([
                'brand_id',
                'category_id',
                'attribute_family_id',
                'slug',
                'special_price',
                'is_active',
                'is_featured',
                'image',
            ]);
        });
    }
};