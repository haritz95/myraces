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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->timestamp('cookie_consented_at')->nullable()->after('theme');
            $table->boolean('cookie_functional')->default(false)->after('cookie_consented_at');
            $table->boolean('cookie_analytics')->default(false)->after('cookie_functional');
        });
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn(['cookie_consented_at', 'cookie_functional', 'cookie_analytics']);
        });
    }
};
