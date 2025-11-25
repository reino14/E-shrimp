# Instalasi Dependencies untuk ML Model

## Instalasi Python Dependencies

Untuk menggunakan fitur prediksi pertumbuhan, Anda perlu menginstall dependencies Python berikut:

### Windows

1. Pastikan Python 3 sudah terinstall (minimal versi 3.8)
   ```powershell
   python --version
   ```

2. Install dependencies menggunakan pip:
   ```powershell
   pip install -r requirements.txt
   ```

   Atau install secara manual:
   ```powershell
   pip install pandas numpy scikit-learn joblib
   ```

### Linux/Mac

1. Pastikan Python 3 sudah terinstall:
   ```bash
   python3 --version
   ```

2. Install dependencies:
   ```bash
   pip3 install -r requirements.txt
   ```

   Atau install secara manual:
   ```bash
   pip3 install pandas numpy scikit-learn joblib
   ```

## Verifikasi Instalasi

Untuk memverifikasi bahwa semua dependencies sudah terinstall dengan benar:

```bash
python -c "import pandas, numpy, sklearn, joblib; print('All dependencies installed successfully!')"
```

## Troubleshooting

### Error: pip tidak ditemukan
- Pastikan Python sudah terinstall dengan benar
- Gunakan `python -m pip` sebagai alternatif

### Error: Permission denied (Linux/Mac)
- Gunakan `pip3 install --user -r requirements.txt` untuk install ke user directory
- Atau gunakan `sudo pip3 install -r requirements.txt` (tidak disarankan)

### Error: ModuleNotFoundError
- Pastikan Python environment yang digunakan sama dengan yang digunakan oleh Laravel
- Cek PATH environment variable

## Catatan

- Dependencies ini hanya diperlukan untuk fitur prediksi pertumbuhan
- Jika model ML belum tersedia, sistem akan menggunakan dummy prediction
- Pastikan model file `random_forest_multi.pkl` sudah ditempatkan di folder `ml_models/`





