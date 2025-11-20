<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thresholds', function (Blueprint $table) {
            $table->id('treshold_id');
            $table->string('kolam_id');
            $table->string('sensor_tipe'); // ph, suhu, oksigen, salinitas
            $table->double('nilai');
            $table->timestamp('timer')->nullable();
            $table->timestamps();

            $table->foreign('kolam_id')->references('kolam_id')->on('dashboard_monitorings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thresholds');
    }
};


