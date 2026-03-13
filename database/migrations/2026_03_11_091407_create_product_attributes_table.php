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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->nullable();

            $table->string('type')->default('text'); 
            // text, number, select, boolean

            $table->string('unit')->nullable();
            // mm, V, L, kg etc

            $table->json('options')->nullable();
            // for select attributes

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
