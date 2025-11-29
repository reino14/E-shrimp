<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->string('kolam_id')->nullable()->after('robot_id');
            $table->integer('umur_budidaya')->nullable()->after('kolam_id');
            
            $table->foreign('kolam_id')->references('kolam_id')->on('dashboard_monitorings')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->dropForeign(['kolam_id']);
            $table->dropColumn(['kolam_id', 'umur_budidaya']);
        });
    }
};










