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
        Schema::table('monitoring_sessions', function (Blueprint $table) {
            $table->text('threshold_oksigen')->nullable()->change();
            $table->text('threshold_ph')->nullable()->change();
            $table->text('threshold_salinitas')->nullable()->change();
            $table->text('threshold_suhu')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_sessions', function (Blueprint $table) {
            $table->double('threshold_oksigen')->nullable()->change();
            $table->double('threshold_ph')->nullable()->change();
            $table->double('threshold_salinitas')->nullable()->change();
            $table->double('threshold_suhu')->nullable()->change();
        });
    }
};
