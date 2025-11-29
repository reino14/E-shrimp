<?php

namespace App\Console\Commands;

use App\Services\PriceTrackerService;
use Illuminate\Console\Command;

class FetchPriceFromAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'price:fetch-api {--test : Test API connection only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch harga udang dari Commodities-API dan simpan ke database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Clear config cache to ensure latest .env is loaded
        \Artisan::call('config:clear');
        
        $apiKey = config('services.commodities_api.key') ?? env('COMMODITIES_API_KEY');
        
        if (!$apiKey || empty(trim($apiKey))) {
            $this->error('❌ COMMODITIES_API_KEY tidak ditemukan di .env file!');
            $this->info('');
            $this->info('Cara setup:');
            $this->info('1. Daftar di: https://commodities-api.com/register');
            $this->info('2. Dapatkan API key');
            $this->info('3. Tambahkan ke .env: COMMODITIES_API_KEY=your_api_key_here');
            $this->info('4. Pastikan tidak ada spasi sebelum/t setelah =');
            $this->info('');
            $this->info('Format yang benar:');
            $this->info('COMMODITIES_API_KEY=xjpkfofnhje1md4gih5lt4aadcxnge6c3h1xrg835p461gdek0eh0ru5j996');
            return 1;
        }

        $this->info('🔑 API Key ditemukan: ' . substr($apiKey, 0, 10) . '...');
        $this->info('');

        if ($this->option('test')) {
            return $this->testAPI($apiKey);
        }

        $this->info('📡 Fetching data dari Commodities-API...');
        $this->info('');

        $service = new PriceTrackerService();
        $data = $service->fetchHargaUdang();

        if ($data && count($data) > 0) {
            $this->info('✅ Data berhasil diambil dan disimpan ke database!');
            $this->info('');
            $this->table(
                ['Ukuran', 'Harga (Rp/kg)', 'Perubahan', 'Trend'],
                array_map(function($item) {
                    return [
                        $item['ukuran'],
                        'Rp ' . number_format($item['harga'], 0, ',', '.'),
                        $item['perubahan'],
                        $item['trend'] === 'up' ? '⬆️ Naik' : '⬇️ Turun',
                    ];
                }, $data)
            );
            $this->info('');
            $this->info('💾 Data tersimpan di database untuk penggunaan selanjutnya.');
        } else {
            $this->error('❌ Gagal mengambil data dari API.');
            $this->info('Sistem akan menggunakan data dari database atau default data.');
        }

        return 0;
    }

    private function testAPI($apiKey)
    {
        $this->info('🧪 Testing API connection...');
        $this->info('');

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(15)
                ->get('https://commodities-api.com/api/latest', [
                    'access_key' => $apiKey,
                    'base' => 'USD',
                    'symbols' => 'SHRIMP,IDR',
                ]);

            $statusCode = $response->status();
            $data = $response->json();
            $body = $response->body();
            
            if ($response->successful()) {
                // Check both possible response structures
                $success = isset($data['data']['success']) ? $data['data']['success'] : (isset($data['success']) ? $data['success'] : false);
                
                if ($success) {
                    $this->info('✅ API Connection: SUCCESS');
                    $this->info('');
                    
                    // Get rates from data.data.rates or data.rates
                    $rates = $data['data']['rates'] ?? $data['rates'] ?? [];
                    
                    if (isset($rates['SHRIMP'])) {
                        $shrimpPrice = $rates['SHRIMP'];
                        $this->info('🦐 Shrimp Price (USD/kg): $' . number_format($shrimpPrice, 4));
                        
                        // Calculate per ton
                        $shrimpPricePerTon = $shrimpPrice * 1000;
                        $this->info('🦐 Shrimp Price (USD/ton): $' . number_format($shrimpPricePerTon, 2));
                    }
                    
                    if (isset($rates['IDR'])) {
                        $usdToIdr = $rates['IDR'];
                        $this->info('💱 USD to IDR: ' . number_format($usdToIdr, 2));
                        
                        // Calculate IDR per kg
                        if (isset($rates['SHRIMP'])) {
                            $shrimpPriceIDRPerKg = $rates['SHRIMP'] * $usdToIdr;
                            $this->info('🦐 Shrimp Price (IDR/kg): Rp ' . number_format($shrimpPriceIDRPerKg, 0));
                        }
                    }
                    
                    $this->info('');
                    $this->info('📊 API Status: Active');
                    $date = $data['data']['date'] ?? $data['date'] ?? 'N/A';
                    $this->info('📅 Last Update: ' . $date);
                    
                    return 0;
                } else {
                    $this->error('❌ API Error: ' . ($data['message'] ?? 'Unknown error'));
                    if (isset($data['error'])) {
                        $this->error('Error Code: ' . ($data['error']['code'] ?? 'N/A'));
                        $this->error('Error Info: ' . ($data['error']['info'] ?? 'N/A'));
                    }
                    $this->info('');
                    $this->info('Response body: ' . substr($body, 0, 500));
                    return 1;
                }
            } else {
                $this->error('❌ HTTP Error: ' . $statusCode);
                $this->error('Response: ' . substr($body, 0, 500));
                if ($data) {
                    $this->error('Error Data: ' . json_encode($data, JSON_PRETTY_PRINT));
                }
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('❌ Exception: ' . $e->getMessage());
            return 1;
        }
    }
}
