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
        Schema::create('races', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->date('date');
            $table->string('location')->nullable();
            $table->string('country')->nullable();
            $table->decimal('distance', 8, 3);
            $table->enum('distance_unit', ['km', 'mi'])->default('km');
            $table->enum('modality', ['road', 'trail', 'mountain', 'track', 'cross', 'other'])->default('road');
            $table->enum('status', ['upcoming', 'completed', 'dnf', 'dns'])->default('upcoming');
            $table->unsignedInteger('finish_time')->nullable()->comment('seconds');
            $table->unsignedInteger('position_overall')->nullable();
            $table->unsignedInteger('position_category')->nullable();
            $table->string('category')->nullable();
            $table->string('bib_number')->nullable();
            $table->decimal('cost', 8, 2)->nullable();
            $table->string('website')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('races');
    }
};
