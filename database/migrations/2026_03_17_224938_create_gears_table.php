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
        Schema::create('gears', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('brand');
            $table->string('model');
            $table->enum('type', ['shoes', 'watch', 'clothing', 'accessories', 'nutrition', 'other'])->default('shoes');
            $table->date('purchase_date')->nullable();
            $table->decimal('current_km', 10, 2)->default(0);
            $table->decimal('max_km', 10, 2)->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gears');
    }
};
