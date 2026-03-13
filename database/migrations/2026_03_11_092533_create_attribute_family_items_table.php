<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_family_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attribute_family_id')
                ->constrained('attribute_families')
                ->cascadeOnDelete();

            $table->foreignId('product_attribute_id')
                ->constrained('product_attributes')
                ->cascadeOnDelete();

            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_required_override')->nullable();
            $table->string('group_name')->nullable();

            $table->timestamps();

            $table->unique(
                ['attribute_family_id', 'product_attribute_id'],
                'afi_family_attribute_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_family_items');
    }
};