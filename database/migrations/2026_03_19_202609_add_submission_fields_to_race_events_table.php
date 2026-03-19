<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('race_events', function (Blueprint $table): void {
            $table->foreignId('submitted_by')->nullable()->after('created_by')
                ->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable()->after('is_featured');
        });
    }

    public function down(): void
    {
        Schema::table('race_events', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('submitted_by');
            $table->dropColumn('rejection_reason');
        });
    }
};
