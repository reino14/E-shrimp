# Quick Start: Training Model ML

## Penjelasan Singkat

**Sebelumnya:** Sistem menggunakan **dummy prediction** (prediksi palsu) yang hanya menghitung nilai berdasarkan formula sederhana.

**Sekarang:** Kita akan melatih model ML yang sebenarnya dari dataset Excel Anda.

## Cara Cepat Training (Windows)

### Opsi 1: Menggunakan Script Batch (Paling Mudah)

1. Double-click file `train.bat` di folder `ml_models`
2. Script akan otomatis:
   - Mengecek Python
   - Install dependencies jika belum ada
   - Mencari file dataset
   - Menjalankan training
3. Tunggu sampai selesai (beberapa menit)
4. Model akan tersimpan di `ml_models/random_forest_multi.pkl`

### Opsi 2: Manual (Command Line)

1. Buka PowerShell atau Command Prompt
2. Masuk ke folder `ml_models`:
   ```powershell
   cd ml_models
   ```
3. Install dependencies (jika belum):
   ```powershell
   pip install -r requirements.txt
   ```
4. Jalankan training:
   ```powershell
   python train_model.py
   ```

## Verifikasi

Setelah training selesai, cek apakah file model sudah dibuat:

```powershell
dir random_forest_multi.pkl
```

Jika file ada, berarti training berhasil!

## Menggunakan Model

Setelah model berhasil ditrain, sistem akan **otomatis** menggunakan model ML yang sebenarnya (bukan dummy lagi) ketika:
- User melakukan prediksi di halaman "Prediksi Pertumbuhan"
- File `random_forest_multi.pkl` ada di folder `ml_models/`

**Tidak perlu konfigurasi tambahan!**

## Troubleshooting

### Error: Python tidak ditemukan
- Install Python dari https://www.python.org/downloads/
- Pastikan Python ditambahkan ke PATH saat instalasi

### Error: pip tidak ditemukan
- Gunakan `python -m pip` sebagai gantinya
- Atau reinstall Python dengan opsi "Add Python to PATH"

### Error: ModuleNotFoundError
```powershell
pip install pandas numpy scikit-learn joblib openpyxl
```

### Error: File dataset tidak ditemukan
- Pastikan file `dataset_e-shrimp.xlsx` ada di root directory (sama level dengan folder `ml_models`)
- Atau gunakan path lengkap: `python train_model.py "C:/path/to/dataset_e-shrimp.xlsx"`

## Catatan Penting

- **Training memakan waktu:** Proses training mungkin memakan waktu beberapa menit tergantung ukuran dataset
- **Model akan ditimpa:** Jika sudah ada model sebelumnya, akan ditimpa dengan model baru
- **Backup disarankan:** Jika ingin menyimpan model lama, rename dulu sebelum training baru

## Format Dataset

Dataset Excel harus memiliki kolom:
- **Features:** Umur_Budidaya, pH, Salinitas_ppt, DO_mgL, Suhu_C
- **Targets:** Berat_udang_gr, Laju_pertumbuhan_harian_gr, Feed_Rate_persen, Pakan_per_hari_kg, Akumulasi_pakan_kg

Lihat `TRAIN.md` untuk informasi lebih detail.





