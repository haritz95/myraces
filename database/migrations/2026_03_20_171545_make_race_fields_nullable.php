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
        Schema::table('races', function (Blueprint $table) {
            $table->decimal('distance', 8, 3)->nullable()->change();
            $table->string('distance_unit', 10)->nullable()->change();
            $table->string('modality', 50)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('races', function (Blueprint $table) {
            $table->decimal('distance', 8, 3)->nullable(false)->change();
            $table->string('distance_unit', 10)->nullable(false)->change();
            $table->string('modality', 50)->nullable(false)->change();
        });
    }
};
