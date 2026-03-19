<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('race_event_modalities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('race_event_id')->constrained()->cascadeOnDelete();
            $table->string('name');                                   // "Maratón", "Media Maratón", "10K"
            $table->decimal('distance_km', 8, 3)->nullable();
            $table->string('category')->nullable();                   // overrides event-level category
            $table->decimal('price', 8, 2)->nullable();
            $table->string('registration_url')->nullable();
            $table->unsignedInteger('max_participants')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('race_event_modalities');
    }
};
