<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoring_sessions', function (Blueprint $table) {
            $table->id('session_id');
            $table->string('kolam_id');
            $table->string('nama_kapal');
            $table->integer('umur_budidaya'); // hari ke-1, 7, 14, etc
            $table->double('threshold_oksigen')->nullable();
            $table->double('threshold_ph')->nullable();
            $table->double('threshold_salinitas')->nullable();
            $table->double('threshold_suhu')->nullable();
            $table->string('timer_monitoring')->default('10'); // setiap 10 detik
            $table->timestamp('mulai_monitoring')->nullable();
            $table->timestamp('selesai_monitoring')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('kolam_id')->references('kolam_id')->on('dashboard_monitorings')->onDelete('cascade');
            // Index for faster queries
            $table->index(['kolam_id', 'umur_budidaya', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_sessions');
    }
};

