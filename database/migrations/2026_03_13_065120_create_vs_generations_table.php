<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vs_generations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('model_id')
                ->constrained('vs_models')
                ->cascadeOnDelete();

            $table->string('name');           // XC90 I
            $table->string('code')->nullable();// 275

            $table->string('body_type')->nullable(); // SUV / Sedan / Wagon / Pickup

            $table->year('year_from');
            $table->year('year_to')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['model_id','year_from','year_to']);
            $table->index('body_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vs_generations');
    }
};