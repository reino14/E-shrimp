# Instruksi Training Model ML

## Cara Training Model

Model ML harus dilatih **terlebih dahulu** sebelum fitur prediksi dapat digunakan. Training dilakukan sekali saja, dan model yang sudah dilatih akan digunakan untuk semua prediksi selanjutnya.

### Langkah-langkah:

1. **Pastikan dataset tersedia**
   - File `dataset_e-shrimp.xlsx` harus ada di root directory project
   - File ini berisi data training untuk model

2. **Jalankan command training**
   ```bash
   php artisan ml:train
   ```

3. **Tunggu proses training selesai**
   - Training biasanya memakan waktu beberapa detik hingga 1-2 menit
   - Progress akan ditampilkan di console
   - Model akan disimpan di `ml_models/random_forest_multi.pkl`

4. **Verifikasi model sudah dibuat**
   - Setelah training selesai, pastikan file `ml_models/random_forest_multi.pkl` ada
   - Jika file sudah ada, model siap digunakan

### Catatan Penting:

- **Training hanya perlu dilakukan sekali** saat pertama kali setup atau jika dataset berubah
- Model yang sudah dilatih akan digunakan untuk semua prediksi
- Jika model tidak ditemukan saat prediksi, sistem akan menampilkan error dan meminta untuk menjalankan training terlebih dahulu
- Peternak **tidak perlu** menjalankan training - ini adalah tugas administrator/developer

### Troubleshooting:

- **Error: Dataset tidak ditemukan**
  - Pastikan file `dataset_e-shrimp.xlsx` ada di root directory project
  
- **Error: Python dependencies tidak ditemukan**
  - Command akan otomatis menginstall dependencies jika belum ada
  - Jika masih error, install manual: `pip install pandas numpy scikit-learn joblib openpyxl`

- **Error: Model tidak tersimpan**
  - Pastikan folder `ml_models` ada dan memiliki permission write
  - Cek log Laravel untuk detail error





