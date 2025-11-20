<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forums', function (Blueprint $table) {
            $table->string('forum_id')->primary();
            $table->string('judul');
            $table->text('isi');
            $table->date('tanggal');
            $table->string('post_peternak_id')->nullable();
            $table->string('email_peternak')->nullable();
            $table->timestamps();

            $table->foreign('email_peternak')->references('email_peternak')->on('peternaks')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forums');
    }
};


