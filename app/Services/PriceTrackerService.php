<?php

namespace App\Services;

use App\Models\HargaUdang;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PriceTrackerService
{
    /**
     * Fetch harga udang vaname from various sources
     * 
     * Priority:
     * 1. Database (primary source - most reliable)
     * 2. Commodities-API (open source API)
     * 3. Default realistic prices (fallback)
     */
    public function fetchHargaUdang()
    {
        // Priority 1: Try database first (most reliable)
        $data = $this->fetchFromDatabase();
        if ($data && count($data) > 0) {
            Log::info('Using price data from database');
            return $data;
        }

        // Priority 2: Try Commodities-API (open source)
        $data = $this->fetchFromCommoditiesAPI();
        if ($data && count($data) > 0) {
            // Save to database for future use
            $this->saveToDatabase($data, 'Commodities-API');
            Log::info('Fetched price data from Commodities-API and saved to database');
            return $data;
        }

        // Priority 3: Return default realistic prices (fallback)
        Log::info('Using default price data');
        $data = $this->getDefaultHargaUdang();
        
        // Save default data to database if database is empty
        if (HargaUdang::count() === 0) {
            $this->saveToDatabase($data, 'Default');
        }
        
        return $data;
    }

    /**
     * Fetch from database (primary source)
     */
    private function fetchFromDatabase()
    {
        try {
            $prices = HargaUdang::getLatestPrices();
            return $prices;
        } catch (\Exception $e) {
            Log::warning('Failed to fetch from database: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Save prices to database
     */
    private function saveToDatabase($prices, $sumber = 'API')
    {
        try {
            $formattedPrices = [];
            foreach ($prices as $price) {
                // Extract ukuran from ukuran_display
                $ukuran = $this->extractUkuran($price['ukuran']);
                
                $formattedPrices[] = [
                    'ukuran' => $ukuran,
                    'ukuran_display' => $price['ukuran'],
                    'harga' => $price['harga'],
                ];
            }
            
            HargaUdang::savePrices($formattedPrices, now()->toDateString(), $sumber);
        } catch (\Exception $e) {
            Log::error('Failed to save prices to database: ' . $e->getMessage());
        }
    }

    /**
     * Extract ukuran code from display string
     */
    private function extractUkuran($ukuranDisplay)
    {
        // Extract "Size 100" from "Size 100 (10g)"
        if (preg_match('/(Size\s*\d+)/i', $ukuranDisplay, $matches)) {
            return $matches[1];
        }
        return $ukuranDisplay;
    }

    /**
     * Fetch from Commodities-API (open source API)
     * 
     * API: https://commodities-api.com/
     * Free tier: 100 requests/month
     * Need API key from: https://commodities-api.com/register
     */
    private function fetchFromCommoditiesAPI()
    {
        $apiKey = env('COMMODITIES_API_KEY');
        
        if (!$apiKey) {
            Log::info('Commodities-API key not set, skipping API fetch');
            return null;
        }

        try {
            // Commodities-API provides shrimp prices in USD per metric ton
            // We need to convert to IDR per kg
            // Correct endpoint: https://commodities-api.com/api/latest
            $response = Http::timeout(15)
                ->get('https://commodities-api.com/api/latest', [
                    'access_key' => $apiKey,
                    'base' => 'USD',
                    'symbols' => 'SHRIMP,IDR',
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Get rates from data.data.rates or data.rates
                $rates = $data['data']['rates'] ?? $data['rates'] ?? [];
                
                if (isset($rates['SHRIMP']) && isset($rates['IDR'])) {
                    // SHRIMP is already in USD per kg (based on response unit)
                    // IDR is USD to IDR exchange rate
                    $shrimpPriceUSDPerKg = $rates['SHRIMP'];
                    $usdToIdr = $rates['IDR'];
                    
                    // Convert to IDR per kg
                    $shrimpPriceIDRPerKg = $shrimpPriceUSDPerKg * $usdToIdr;

                    // Create price data for different sizes
                    // Base price is for average size, adjust for different sizes
                    $basePrice = (int) $shrimpPriceIDRPerKg;
                    
                    return $this->generatePricesFromBase($basePrice);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to fetch from Commodities-API: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get USD to IDR exchange rate
     */
    private function getUSDToIDR()
    {
        try {
            // Try to get from Commodities-API
            $apiKey = env('COMMODITIES_API_KEY');
            if ($apiKey) {
                $response = Http::timeout(5)
                    ->get('https://api.commodities-api.com/v1/latest', [
                        'access_key' => $apiKey,
                        'base' => 'USD',
                        'symbols' => 'IDR',
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['data']['rates']['IDR'])) {
                        return $data['data']['rates']['IDR'];
                    }
                }
            }

            // Fallback: Use approximate rate (update as needed)
            return 16000; // Approximate USD to IDR rate
        } catch (\Exception $e) {
            Log::warning('Failed to get USD to IDR rate: ' . $e->getMessage());
            return 16000; // Fallback rate
        }
    }

    /**
     * Generate prices for different sizes from base price
     */
    private function generatePricesFromBase($basePrice)
    {
        // Size multipliers (smaller = cheaper, larger = more expensive)
        $multipliers = [
            'Size 100' => 0.6,  // 60% of base (smallest)
            'Size 80' => 0.7,   // 70% of base
            'Size 60' => 0.8,   // 80% of base
            'Size 50' => 0.9,   // 90% of base
            'Size 40' => 1.0,   // 100% of base (average)
            'Size 30' => 1.15,  // 115% of base (largest)
        ];

        $hargaUdang = [];
        $lastPrices = Cache::get('harga_udang_last', []);

        foreach ($multipliers as $size => $multiplier) {
            $harga = (int) ($basePrice * $multiplier);
            $lastHarga = $lastPrices[$size] ?? $harga;
            $perubahan = $harga - $lastHarga;
            $persentase = $lastHarga > 0 ? ($perubahan / $lastHarga) * 100 : 0;

            $hargaUdang[] = [
                'ukuran' => $size . ' (' . $this->getSizeInGram($size) . 'g)',
                'harga' => $harga,
                'perubahan' => ($persentase >= 0 ? '+' : '') . number_format($persentase, 1) . '%',
                'trend' => $persentase >= 0 ? 'up' : 'down',
            ];
        }

        // Cache current prices
        $priceMap = [];
        foreach ($multipliers as $size => $multiplier) {
            $priceMap[$size] = (int) ($basePrice * $multiplier);
        }
        Cache::put('harga_udang_last', $priceMap, now()->addDays(1));

        return $hargaUdang;
    }


    /**
     * Get default harga udang (realistic market prices)
     */
    private function getDefaultHargaUdang()
    {
        $basePrices = [
            'Size 100' => 48000,
            'Size 80' => 55000,
            'Size 60' => 62000,
            'Size 50' => 70000,
            'Size 40' => 78000,
            'Size 30' => 90000,
        ];

        $lastPrices = Cache::get('harga_udang_last', $basePrices);
        $hargaUdang = [];

        foreach ($basePrices as $size => $harga) {
            $lastHarga = $lastPrices[$size] ?? $harga;
            $perubahan = $harga - $lastHarga;
            $persentase = $lastHarga > 0 ? ($perubahan / $lastHarga) * 100 : 0;

            // Small random variation (±2%)
            $variation = (rand(-200, 200) / 10000) * $harga;
            $finalHarga = round($harga + $variation);

            $hargaUdang[] = [
                'ukuran' => $size . ' (' . $this->getSizeInGram($size) . 'g)',
                'harga' => $finalHarga,
                'perubahan' => ($persentase >= 0 ? '+' : '') . number_format($persentase, 1) . '%',
                'trend' => $persentase >= 0 ? 'up' : 'down',
            ];
        }

        Cache::put('harga_udang_last', $basePrices, now()->addDays(1));
        return $hargaUdang;
    }

    private function getSizeInGram($size)
    {
        $sizeMap = [
            'Size 100' => '10',
            'Size 80' => '12.5',
            'Size 60' => '16.7',
            'Size 50' => '20',
            'Size 40' => '25',
            'Size 30' => '33.3',
        ];

        return $sizeMap[$size] ?? '0';
    }
}

