# Price Tracker Setup Guide

## ✅ Solusi Baru (Tanpa Jala Tech)

Sistem sekarang menggunakan:
1. **Database** (Primary) - Data disimpan di database sendiri
2. **Commodities-API** (Optional) - API open source untuk update otomatis
3. **Default Data** (Fallback) - Data realistis jika API tidak tersedia

## 🚀 Setup

### 1. Database sudah dibuat
Migration dan seeder sudah dijalankan. Data awal sudah ada di database.

### 2. Setup Commodities-API (Optional)

Commodities-API adalah API open source yang menyediakan data harga komoditas termasuk udang.

**Langkah-langkah:**

1. Daftar di: https://commodities-api.com/register
2. Dapatkan API key (Free tier: 100 requests/month)
3. Tambahkan ke `.env`:
   ```env
   COMMODITIES_API_KEY=your_api_key_here
   ```

**Catatan:**
- Free tier cukup untuk update harian
- Jika tidak ada API key, sistem akan menggunakan data dari database
- Data dari API akan otomatis disimpan ke database

### 3. Update Data Manual (Jika Perlu)

Jika ingin update data manual, gunakan tinker:

```bash
php artisan tinker
```

```php
use App\Models\HargaUdang;

// Update harga untuk hari ini
$prices = [
    ['ukuran' => 'Size 100', 'ukuran_display' => 'Size 100 (10g)', 'harga' => 48000],
    ['ukuran' => 'Size 80', 'ukuran_display' => 'Size 80 (12.5g)', 'harga' => 55000],
    ['ukuran' => 'Size 60', 'ukuran_display' => 'Size 60 (16.7g)', 'harga' => 62000],
    ['ukuran' => 'Size 50', 'ukuran_display' => 'Size 50 (20g)', 'harga' => 70000],
    ['ukuran' => 'Size 40', 'ukuran_display' => 'Size 40 (25g)', 'harga' => 78000],
    ['ukuran' => 'Size 30', 'ukuran_display' => 'Size 30 (33.3g)', 'harga' => 90000],
];

HargaUdang::savePrices($prices, now()->toDateString(), 'Manual');
```

## 📊 Cara Kerja

### Flow Data

```
User Request → priceTracker()
    ↓
Check Cache (6 jam)
    ↓ (cache miss)
PriceTrackerService::fetchHargaUdang()
    ↓
Priority 1: Database (harga_udang table)
    ↓ (jika ada data)
Return data dari database
    ↓ (jika tidak ada)
Priority 2: Commodities-API
    ↓ (jika berhasil)
Save ke database → Return data
    ↓ (jika gagal)
Priority 3: Default Data
    ↓
Save ke database (jika kosong) → Return data
```

### Sumber Data

1. **Database** (`harga_udang` table)
   - Data paling reliable
   - Bisa di-update manual atau otomatis dari API
   - Menyimpan history perubahan harga

2. **Commodities-API**
   - API open source
   - Free tier: 100 requests/month
   - Data otomatis disimpan ke database setelah fetch

3. **Default Data**
   - Harga realistis berdasarkan pasar Indonesia
   - Digunakan jika database kosong dan API gagal

## 🔄 Update Otomatis

Sistem akan otomatis:
- Menggunakan data dari database jika tersedia
- Fetch dari API jika database kosong
- Menyimpan data API ke database untuk penggunaan selanjutnya
- Menghitung perubahan harga otomatis

## 📝 Struktur Database

Table: `harga_udang`

```sql
- id
- ukuran (Size 100, Size 80, etc.)
- ukuran_display (Size 100 (10g))
- harga (decimal 12,2)
- tanggal (date)
- sumber (Database, Commodities-API, Manual, etc.)
- harga_sebelumnya (untuk kalkulasi perubahan)
- timestamps
```

## 🎯 Keuntungan Sistem Baru

1. ✅ **Tidak bergantung pada web scraping** - Lebih stabil
2. ✅ **Database sendiri** - Data bisa di-manage sendiri
3. ✅ **API open source** - Legal dan reliable
4. ✅ **History tracking** - Bisa lihat perubahan harga
5. ✅ **Flexible** - Bisa update manual atau otomatis

## 🔧 Troubleshooting

### Data tidak muncul
- Cek apakah seeder sudah dijalankan: `php artisan db:seed --class=HargaUdangSeeder`
- Cek database: `SELECT * FROM harga_udang;`

### API tidak bekerja
- Cek API key di `.env`
- Cek log: `storage/logs/laravel.log`
- Sistem akan fallback ke database/default data

### Ingin reset data
```bash
php artisan migrate:fresh --seed
```

---

**Last Updated**: November 2025
**Status**: ✅ Production Ready (No Jala Tech dependency)

