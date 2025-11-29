<?php

namespace Database\Seeders;

use App\Models\HargaUdang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class HargaUdangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::today();
        
        // Default realistic prices for Indonesia market (November 2025)
        $prices = [
            [
                'ukuran' => 'Size 100',
                'ukuran_display' => 'Size 100 (10g)',
                'harga' => 48000,
            ],
            [
                'ukuran' => 'Size 80',
                'ukuran_display' => 'Size 80 (12.5g)',
                'harga' => 55000,
            ],
            [
                'ukuran' => 'Size 60',
                'ukuran_display' => 'Size 60 (16.7g)',
                'harga' => 62000,
            ],
            [
                'ukuran' => 'Size 50',
                'ukuran_display' => 'Size 50 (20g)',
                'harga' => 70000,
            ],
            [
                'ukuran' => 'Size 40',
                'ukuran_display' => 'Size 40 (25g)',
                'harga' => 78000,
            ],
            [
                'ukuran' => 'Size 30',
                'ukuran_display' => 'Size 30 (33.3g)',
                'harga' => 90000,
            ],
        ];

        foreach ($prices as $price) {
            HargaUdang::create([
                'ukuran' => $price['ukuran'],
                'ukuran_display' => $price['ukuran_display'],
                'harga' => $price['harga'],
                'tanggal' => $today,
                'sumber' => 'Seeder',
                'harga_sebelumnya' => null,
            ]);
        }
    }
}
