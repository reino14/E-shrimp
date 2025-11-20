<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboard_monitorings', function (Blueprint $table) {
            $table->string('kolam_id')->primary();
            $table->string('email_peternak');
            $table->string('foto_posisi_kapal')->nullable();
            $table->string('treshold_id')->nullable();
            $table->string('notif_id')->nullable();
            $table->unsignedBigInteger('data_id')->nullable();
            $table->timestamps();

            $table->foreign('email_peternak')->references('email_peternak')->on('peternaks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_monitorings');
    }
};


