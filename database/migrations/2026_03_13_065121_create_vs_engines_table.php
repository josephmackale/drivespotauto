<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vs_engines', function (Blueprint $table) {
            $table->id();

            $table->foreignId('generation_id')
                ->constrained('vs_generations')
                ->cascadeOnDelete();

            $table->string('engine_code'); // B5254T2

            $table->string('engine_family')->nullable(); // B5254

            $table->integer('capacity_cc')->nullable();
            $table->decimal('capacity_l',4,1)->nullable();

            $table->string('fuel_type')->nullable(); // Petrol / Diesel / Hybrid
            $table->integer('power_hp')->nullable();

            $table->string('drivetrain')->nullable(); // AWD / FWD / RWD

            $table->year('year_from')->nullable();
            $table->year('year_to')->nullable();

            $table->string('canonical_key')->unique();

            $table->timestamps();

            $table->index(['generation_id','engine_code']);
            $table->index('engine_family');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vs_engines');
    }
};