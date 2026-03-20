<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vs_models', function (Blueprint $table) {
            $table->id();

            $table->foreignId('make_id')
                ->constrained('vs_makes')
                ->cascadeOnDelete();

            $table->string('name'); // XC90
            $table->string('slug'); // xc90

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['make_id','slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vs_models');
    }
};