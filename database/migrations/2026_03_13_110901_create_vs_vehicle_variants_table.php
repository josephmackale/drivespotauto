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
        Schema::create('vs_vehicle_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('generation_id')
                ->constrained('vs_generations')
                ->cascadeOnDelete();

            $table->foreignId('engine_id')
                ->nullable()
                ->constrained('vs_engines')
                ->nullOnDelete();

            $table->string('variant_name');        // A 220 CDI 4-matic
            $table->string('type_code')->nullable(); // 176.005

            $table->string('engine_code')->nullable();

            $table->integer('power_kw')->nullable();
            $table->integer('power_hp')->nullable();

            $table->integer('capacity_cc')->nullable();
            $table->decimal('capacity_l',4,1)->nullable();

            $table->string('fuel_type')->nullable();

            // Only store when TecDoc explicitly indicates (e.g. 4-matic)
            $table->string('drivetrain')->nullable();

            $table->string('body_type')->nullable();

            $table->year('year_from')->nullable();
            $table->year('year_to')->nullable();

            $table->string('vehicle_label')->nullable();

            $table->string('key_canonical')->nullable()->unique();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vs_vehicle_variants');
    }
};
