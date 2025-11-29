@echo off
echo ========================================
echo E-SHRIMP Model Training
echo ========================================
echo.

cd /d %~dp0

echo Checking Python installation...
python --version
if errorlevel 1 (
    echo ERROR: Python tidak ditemukan!
    echo Silakan install Python terlebih dahulu.
    pause
    exit /b 1
)

echo.
echo Checking dependencies...
python -c "import pandas, numpy, sklearn, joblib, openpyxl" 2>nul
if errorlevel 1 (
    echo Installing dependencies...
    pip install -r requirements.txt
    if errorlevel 1 (
        echo ERROR: Gagal menginstall dependencies!
        pause
        exit /b 1
    )
)

echo.
echo Checking dataset file...
if not exist "..\dataset_e-shrimp.xlsx" (
    echo WARNING: File dataset_e-shrimp.xlsx tidak ditemukan di root directory!
    echo Pastikan file Excel berada di: %cd%\..\dataset_e-shrimp.xlsx
    echo.
    set /p dataset_path="Masukkan path lengkap ke file dataset (atau tekan Enter untuk skip): "
    if "!dataset_path!"=="" (
        echo Training dibatalkan.
        pause
        exit /b 1
    )
    python train_model.py "!dataset_path!"
) else (
    echo Dataset ditemukan: ..\dataset_e-shrimp.xlsx
    echo.
    echo Starting training...
    python train_model.py
)

if errorlevel 1 (
    echo.
    echo ERROR: Training gagal!
    pause
    exit /b 1
)

echo.
echo ========================================
echo Training selesai!
echo ========================================
pause






