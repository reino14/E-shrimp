<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id('notif_id');
            $table->string('kolam_id');
            $table->text('pesan');
            $table->timestamp('waktu');
            $table->boolean('status')->default(false); // false = unread, true = read
            $table->timestamps();

            $table->foreign('kolam_id')->references('kolam_id')->on('dashboard_monitorings')->onDelete('cascade');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};


