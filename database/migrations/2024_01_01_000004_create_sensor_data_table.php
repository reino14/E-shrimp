<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id('data_id');
            $table->string('robot_id');
            $table->timestamp('waktu');
            $table->double('suhu');
            $table->double('oksigen');
            $table->double('ph');
            $table->double('salinitas');
            $table->string('kualitas_air')->nullable();
            $table->timestamps();

            $table->foreign('robot_id')->references('robot_id')->on('robot_kapal_eshrimps')->onDelete('cascade');
            $table->index('waktu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};


