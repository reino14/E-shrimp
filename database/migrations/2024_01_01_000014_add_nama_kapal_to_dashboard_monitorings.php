<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dashboard_monitorings', function (Blueprint $table) {
            $table->string('nama_kapal')->nullable()->after('kolam_id');
            $table->index('nama_kapal');
        });
    }

    public function down(): void
    {
        Schema::table('dashboard_monitorings', function (Blueprint $table) {
            $table->dropIndex(['nama_kapal']);
            $table->dropColumn('nama_kapal');
        });
    }
};







