<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('robot_kapal_eshrimps', function (Blueprint $table) {
            $table->string('robot_id')->primary();
            $table->string('email_peternak');
            $table->string('status')->default('idle');
            $table->string('lokasi')->nullable();
            $table->timestamps();

            $table->foreign('email_peternak')->references('email_peternak')->on('peternaks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('robot_kapal_eshrimps');
    }
};


