# Training Model ML untuk E-SHRIMP

## Penjelasan

Sebelumnya sistem menggunakan **dummy prediction** (prediksi palsu) yang hanya menghitung nilai berdasarkan formula sederhana. Sekarang kita akan melatih model ML yang sebenarnya dari dataset Excel yang Anda berikan.

## Cara Training Model

### 1. Install Dependencies

Pastikan semua dependencies sudah terinstall:

```bash
pip install -r requirements.txt
```

Atau install secara manual:

```bash
pip install pandas numpy scikit-learn joblib openpyxl
```

**Catatan:** `openpyxl` diperlukan untuk membaca file Excel.

### 2. Pastikan File Dataset Tersedia

File `dataset_e-shrimp.xlsx` harus berada di root directory project (sama level dengan folder `ml_models`).

Struktur yang diharapkan:
```
E-shrimp/
├── dataset_e-shrimp.xlsx  ← File dataset di sini
├── ml_models/
│   ├── train_model.py
│   ├── predict_growth.py
│   └── random_forest_multi.pkl  ← Model akan disimpan di sini
```

### 3. Jalankan Training

#### Windows (PowerShell):
```powershell
cd ml_models
python train_model.py
```

#### Linux/Mac:
```bash
cd ml_models
python3 train_model.py
```

#### Dengan path custom:
```bash
python train_model.py ../dataset_e-shrimp.xlsx random_forest_multi.pkl
```

### 4. Verifikasi Model

Setelah training selesai, model akan disimpan di `ml_models/random_forest_multi.pkl`.

Untuk memverifikasi model bisa digunakan:

```bash
python predict_growth.py '{"Umur_Budidaya":14,"pH":8.0,"Salinitas_ppt":20,"DO_mgL":6.0,"Suhu_C":30}' random_forest_multi.pkl
```

## Format Dataset yang Diperlukan

Dataset Excel harus memiliki kolom berikut:

### Features (Input):
- `Umur_Budidaya` (integer)
- `pH` (float)
- `Salinitas_ppt` (float)
- `DO_mgL` (float) - Dissolved Oxygen
- `Suhu_C` (float) - Temperature

### Targets (Output):
- `Berat_udang_gr` (float)
- `Laju_pertumbuhan_harian_gr` (float)
- `Feed_Rate_persen` (float)
- `Pakan_per_hari_kg` (float)
- `Akumulasi_pakan_kg` (float)

## Output Training

Script akan menampilkan:
1. **Dataset Info**: Shape dan kolom yang tersedia
2. **Data Preparation**: Jumlah data setelah cleaning
3. **Train-Test Split**: Pembagian data training dan testing
4. **Model Training**: Proses training (mungkin memakan waktu beberapa menit)
5. **Evaluation Metrics**: 
   - MAE (Mean Absolute Error)
   - RMSE (Root Mean Squared Error)
   - R² Score
6. **Overfitting Check**: Peringatan jika model overfitting atau underfitting
7. **Model Save**: Konfirmasi model berhasil disimpan

## Troubleshooting

### Error: File tidak ditemukan
- Pastikan file `dataset_e-shrimp.xlsx` berada di root directory
- Atau gunakan path lengkap: `python train_model.py "C:/path/to/dataset_e-shrimp.xlsx"`

### Error: Kolom tidak ditemukan
- Pastikan nama kolom di Excel sesuai dengan yang diperlukan
- Nama kolom case-sensitive (harus sama persis)

### Error: ModuleNotFoundError: No module named 'openpyxl'
```bash
pip install openpyxl
```

### Error: Tidak ada data yang valid
- Pastikan dataset tidak kosong
- Pastikan tidak ada terlalu banyak nilai NaN
- Cek apakah semua kolom yang diperlukan ada datanya

## Setelah Training

Setelah model berhasil ditrain dan disimpan, sistem akan otomatis menggunakan model ML yang sebenarnya (bukan dummy prediction lagi) ketika:
1. File `random_forest_multi.pkl` ada di folder `ml_models/`
2. User melakukan prediksi melalui halaman "Prediksi Pertumbuhan"

## Catatan

- Model akan ditrain dengan **300 estimators** (pohon keputusan)
- Data akan dibagi **80% training** dan **20% testing**
- Model menggunakan **Random Forest Multi-output Regressor** untuk memprediksi 5 target sekaligus
- Training mungkin memakan waktu beberapa menit tergantung ukuran dataset






