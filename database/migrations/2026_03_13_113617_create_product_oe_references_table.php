<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {   
        if (!Schema::hasTable('product_oe_references')) {
            Schema::create('product_oe_references', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('product_id');

                $table->string('brand_name_raw');
                $table->string('brand_name_normalized');

                $table->string('reference_number_raw');
                $table->string('reference_number_normalized');

                $table->string('reference_type')->default('OE');

                $table->integer('sort_order')->nullable();

                $table->timestamps();

                $table->index('product_id');
                $table->index('brand_name_normalized');
                $table->index('reference_number_normalized');

                $table->unique([
                    'product_id',
                    'brand_name_normalized',
                    'reference_number_normalized'
                ], 'uq_product_oe_unique');

                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_oe_references');
    }
};
