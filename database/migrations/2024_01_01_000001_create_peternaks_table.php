<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peternaks', function (Blueprint $table) {
            $table->string('email_peternak')->primary();
            $table->string('nama');
            $table->string('password');
            $table->string('tracker_id')->nullable();
            $table->string('role')->default('peternak');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peternaks');
    }
};


