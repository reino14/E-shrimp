# Panduan Penggunaan API Price Tracker

## ✅ API Key Sudah Ditambahkan

Setelah menambahkan `COMMODITIES_API_KEY` di file `.env`, ikuti langkah berikut:

## 🧪 Test API Connection

Pertama, test apakah API key bekerja dengan baik:

```bash
php artisan price:fetch-api --test
```

**Output yang diharapkan:**
```
✅ API Connection: SUCCESS
🦐 Shrimp Price (USD/ton): $X,XXX.XX
💱 USD to IDR: 16,XXX.XX
📊 API Status: Active
```

Jika ada error, periksa:
- API key sudah benar di `.env`
- API key masih aktif (belum expired)
- Koneksi internet stabil

## 📥 Fetch Data dari API

Setelah API connection berhasil, fetch data dan simpan ke database:

```bash
php artisan price:fetch-api
```

**Output yang diharapkan:**
```
✅ Data berhasil diambil dan disimpan ke database!

┌─────────────────┬──────────────┬────────────┬─────────┐
│ Ukuran          │ Harga (Rp/kg)│ Perubahan  │ Trend   │
├─────────────────┼──────────────┼────────────┼─────────┤
│ Size 100 (10g)  │ Rp 48,000    │ +2.3%      │ ⬆️ Naik │
│ Size 80 (12.5g) │ Rp 55,000    │ +1.8%      │ ⬆️ Naik │
│ ...             │ ...          │ ...        │ ...     │
└─────────────────┴──────────────┴────────────┴─────────┘

💾 Data tersimpan di database untuk penggunaan selanjutnya.
```

## 🔄 Update Otomatis

Sistem akan otomatis:
1. **Cek database dulu** - Jika ada data terbaru, gunakan itu
2. **Fetch dari API** - Jika database kosong atau data lama
3. **Simpan ke database** - Data dari API otomatis disimpan
4. **Gunakan default** - Jika API gagal

## 🌐 Melalui Web Interface

Anda juga bisa update data melalui web:

1. Buka halaman **Price Tracker**
2. Klik tombol **"Refresh"**
3. Sistem akan otomatis fetch dari API (jika database kosong) atau dari database

## 📊 Cek Data di Database

Untuk melihat data yang sudah tersimpan:

```bash
php artisan tinker
```

```php
use App\Models\HargaUdang;

// Lihat data terbaru
HargaUdang::latest('tanggal')->get();

// Lihat semua data
HargaUdang::all();

// Lihat data hari ini
HargaUdang::where('tanggal', today())->get();
```

## 🔧 Troubleshooting

### Error: "COMMODITIES_API_KEY tidak ditemukan"
- Pastikan sudah menambahkan `COMMODITIES_API_KEY=your_key` di file `.env`
- Restart server setelah edit `.env`

### Error: "API Error: Invalid API key"
- Cek API key di https://commodities-api.com/dashboard
- Pastikan API key masih aktif (tidak expired)
- Free tier: 100 requests/month

### Error: "Failed to fetch from Commodities-API"
- Cek koneksi internet
- Cek log: `storage/logs/laravel.log`
- Sistem akan fallback ke database/default data

### Data tidak update
- Cek apakah data sudah ada di database dengan tanggal hari ini
- Hapus cache: `php artisan cache:clear`
- Force refresh: `php artisan price:fetch-api`

## 📅 Schedule Update (Optional)

Untuk update otomatis setiap hari, tambahkan ke `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Update harga setiap hari jam 8 pagi
    $schedule->command('price:fetch-api')
        ->dailyAt('08:00');
}
```

## 📝 Catatan Penting

1. **Free Tier Limit**: 100 requests/month
   - Cukup untuk update harian (30 requests/month)
   - Jangan fetch terlalu sering

2. **Data Priority**:
   - Database > API > Default
   - Data dari database lebih diutamakan

3. **History Tracking**:
   - Setiap update menyimpan data dengan tanggal baru
   - Perubahan harga dihitung otomatis

4. **Manual Update**:
   - Bisa update manual via tinker atau admin panel
   - Data manual akan di-prioritaskan

---

**Status**: ✅ API Ready
**Last Updated**: November 2025

