<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('monitoring_sessions', function (Blueprint $table) {
            // Change enum to string
            $table->string('timer_monitoring')->default('10')->change();
        });
    }

    public function down(): void
    {
        Schema::table('monitoring_sessions', function (Blueprint $table) {
            // Revert to enum if needed
            $table->enum('timer_monitoring', ['1', '3', '6'])->change();
        });
    }
};










