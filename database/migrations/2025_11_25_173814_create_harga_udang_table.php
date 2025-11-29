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
        Schema::create('harga_udang', function (Blueprint $table) {
            $table->id();
            $table->string('ukuran'); // Size 100, Size 80, etc.
            $table->string('ukuran_display'); // Size 100 (10g)
            $table->decimal('harga', 12, 2); // Harga per kg
            $table->date('tanggal'); // Tanggal harga berlaku
            $table->string('sumber')->nullable(); // Database, Commodities-API, Manual, etc.
            $table->decimal('harga_sebelumnya', 12, 2)->nullable(); // Untuk kalkulasi perubahan
            $table->timestamps();
            
            // Index untuk query cepat
            $table->index('tanggal');
            $table->index('ukuran');
            $table->index(['tanggal', 'ukuran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_udang');
    }
};
