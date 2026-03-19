<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('race_events', function (Blueprint $table): void {
            $table->string('image_url')->nullable()->after('image');
            $table->string('category')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('race_events', function (Blueprint $table): void {
            $table->dropColumn('image_url');
            $table->string('category')->nullable(false)->change();
        });
    }
};
