<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_sessions', function (Blueprint $table) {
            $table->timestamp('paused_at')->nullable()->after('mulai_monitoring');
            $table->timestamp('resumed_at')->nullable()->after('paused_at');
            $table->integer('total_paused_seconds')->default(0)->after('resumed_at'); // Total waktu pause dalam detik
            $table->boolean('is_paused')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_sessions', function (Blueprint $table) {
            $table->dropColumn(['paused_at', 'resumed_at', 'total_paused_seconds', 'is_paused']);
        });
    }
};







