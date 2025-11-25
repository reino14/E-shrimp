# ML Models Directory

## Setup Instructions

### 1. Install Python Dependencies

Install semua dependencies yang diperlukan:

```bash
pip install -r requirements.txt
```

Atau install secara manual:
```bash
pip install pandas numpy scikit-learn joblib openpyxl
```

**Catatan:** `openpyxl` diperlukan untuk membaca file Excel dataset.

### 2. Training Model dari Dataset Excel

**PENTING:** Sebelumnya sistem menggunakan **dummy prediction** (prediksi palsu). Untuk menggunakan model ML yang sebenarnya, Anda perlu melatih model terlebih dahulu.

#### Langkah-langkah Training:

1. **Pastikan file dataset tersedia:**
   - File `dataset_e-shrimp.xlsx` harus berada di root directory (sama level dengan folder `ml_models`)
   - Struktur: `E-shrimp/dataset_e-shrimp.xlsx`

2. **Jalankan script training:**
   ```bash
   cd ml_models
   python train_model.py
   ```

3. **Tunggu proses training selesai:**
   - Proses mungkin memakan waktu beberapa menit
   - Model akan disimpan sebagai `random_forest_multi.pkl`

4. **Verifikasi model:**
   - Setelah training selesai, model akan otomatis digunakan oleh sistem
   - Tidak perlu konfigurasi tambahan

Lihat `TRAIN.md` untuk instruksi lengkap tentang training model.

3. **Make sure the Python script is executable (Linux/Mac):**
   ```bash
   chmod +x predict_growth.py
   ```

4. **Test the Python script manually (optional):**
   ```bash
   python predict_growth.py '{"Umur_Budidaya":14,"pH":8.0,"Salinitas_ppt":20,"DO_mgL":6.0,"Suhu_C":30}' random_forest_multi.pkl
   ```

## Model File Requirements

The model file should be saved as: `random_forest_multi.pkl`

This model should be trained using the code provided, which uses:
- **Features**: `Umur_Budidaya`, `pH`, `Salinitas_ppt`, `DO_mgL`, `Suhu_C`
- **Targets**: 
  - `Berat_udang_gr`
  - `Laju_pertumbuhan_harian_gr`
  - `Feed_Rate_persen`
  - `Pakan_per_hari_kg`
  - `Akumulasi_pakan_kg`

## How It Works

1. User clicks "Mulai Prediksi" button on the prediction page
2. System validates that monitoring has reached day 14
3. System collects daily average sensor data (every 7 days: 1, 7, 14, 21, etc.)
4. System uses the latest data point to call the ML model
5. Python script loads the model and makes prediction
6. Results are displayed in the UI

## Data Flow

- **Input**: Latest daily average sensor data from monitoring (pH, Salinitas, DO, Suhu, Umur_Budidaya)
- **Processing**: Random Forest Multi-output Regressor predicts 5 target values
- **Output**: Predicted values for all 5 targets displayed in the UI

## Troubleshooting

- If model file is not found, system will use dummy prediction values
- Check Laravel logs for Python script errors
- Ensure Python path is correct (python3 on Linux/Mac, python on Windows)
- Verify model file format matches the expected structure
