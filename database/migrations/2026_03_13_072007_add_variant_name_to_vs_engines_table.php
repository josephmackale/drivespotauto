<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vs_engines', function (Blueprint $table) {
            $table->string('variant_name')->nullable()->after('generation_id');
            $table->index('variant_name');
        });
    }

    public function down(): void
    {
        Schema::table('vs_engines', function (Blueprint $table) {
            $table->dropIndex(['variant_name']);
            $table->dropColumn('variant_name');
        });
    }
};