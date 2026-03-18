<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('race_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            // Identity
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            // When & where
            $table->dateTime('event_date');
            $table->date('registration_deadline')->nullable();
            $table->string('location');          // City / venue
            $table->string('province')->nullable();
            $table->string('country')->default('España');

            // Race details
            $table->decimal('distance_km', 8, 3)->nullable();
            $table->string('category');          // 5K, 10K, Maratón, Trail, etc.
            $table->string('race_type');         // road, trail, mountain, ultra, obstacle, triathlon, other
            $table->decimal('price', 8, 2)->nullable();
            $table->unsignedInteger('max_participants')->nullable();

            // Links
            $table->string('website_url')->nullable();
            $table->string('registration_url')->nullable();  // future affiliate link
            $table->string('organizer')->nullable();

            // Status & source
            $table->string('status')->default('upcoming'); // upcoming, open, cancelled, past
            $table->string('source')->default('manual');   // manual | future: api_ahotu, api_x, etc.
            $table->string('external_id')->nullable();     // dedup key for future API imports
            $table->boolean('is_featured')->default(false);

            $table->timestamps();

            $table->index(['event_date', 'status']);
            $table->index('race_type');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('race_events');
    }
};
