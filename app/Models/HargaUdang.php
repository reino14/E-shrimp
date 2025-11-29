<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaUdang extends Model
{
    protected $table = 'harga_udang';
    
    protected $fillable = [
        'ukuran',
        'ukuran_display',
        'harga',
        'tanggal',
        'sumber',
        'harga_sebelumnya',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'harga_sebelumnya' => 'decimal:2',
        'tanggal' => 'date',
    ];

    /**
     * Get latest prices for all sizes
     */
    public static function getLatestPrices()
    {
        $latestDate = self::max('tanggal');
        
        if (!$latestDate) {
            return [];
        }

        return self::where('tanggal', $latestDate)
            ->orderBy('harga', 'asc')
            ->get()
            ->map(function($item) {
                // Calculate perubahan
                $perubahan = 0;
                $persentase = 0;
                
                if ($item->harga_sebelumnya) {
                    $perubahan = $item->harga - $item->harga_sebelumnya;
                    $persentase = ($perubahan / $item->harga_sebelumnya) * 100;
                }

                return [
                    'ukuran' => $item->ukuran_display,
                    'harga' => (int) $item->harga,
                    'perubahan' => ($persentase >= 0 ? '+' : '') . number_format($persentase, 1) . '%',
                    'trend' => $persentase >= 0 ? 'up' : 'down',
                    'sumber' => $item->sumber ?? 'Database',
                ];
            })
            ->toArray();
    }

    /**
     * Save or update prices for a date
     */
    public static function savePrices($prices, $tanggal, $sumber = 'Manual')
    {
        // Get previous prices for comparison
        $previousDate = self::where('tanggal', '<', $tanggal)
            ->max('tanggal');
        
        $previousPrices = [];
        if ($previousDate) {
            $previousPrices = self::where('tanggal', $previousDate)
                ->pluck('harga', 'ukuran')
                ->toArray();
        }

        foreach ($prices as $price) {
            self::updateOrCreate(
                [
                    'ukuran' => $price['ukuran'],
                    'tanggal' => $tanggal,
                ],
                [
                    'ukuran_display' => $price['ukuran_display'] ?? $price['ukuran'],
                    'harga' => $price['harga'],
                    'sumber' => $sumber,
                    'harga_sebelumnya' => $previousPrices[$price['ukuran']] ?? null,
                ]
            );
        }
    }
}
