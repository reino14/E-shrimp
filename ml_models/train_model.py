#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
E-SHRIMP: Random Forest Model Training Script
Melatih model dari dataset Excel dan menyimpan model yang sudah ditrain
"""

import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.multioutput import MultiOutputRegressor
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics import mean_absolute_error, mean_squared_error, r2_score
import joblib
import os
import sys
import json
import time

# ================================================================
#                E-SHRIMP : Random Forest Regression
#        Dengan Sistem Prediksi Berdasarkan HISTORI (Umur <= X)
# ================================================================

def output_progress(step, total_steps, message, progress_file=None):
    """Output progress in JSON format for frontend"""
    progress = {
        'step': step,
        'total_steps': total_steps,
        'percentage': int((step / total_steps) * 100),
        'message': message,
        'timestamp': time.time()
    }
    
    # Print to stdout (for real-time viewing)
    print(f"PROGRESS:{json.dumps(progress)}", flush=True)
    
    # Also write to progress file if provided
    if progress_file:
        try:
            with open(progress_file, 'w') as f:
                json.dump(progress, f)
        except:
            pass

def train_model(dataset_path='../dataset_e-shrimp.xlsx', model_output_path='random_forest_multi.pkl', progress_file=None):
    """
    Melatih model Random Forest dari dataset Excel
    
    Args:
        dataset_path: Path ke file Excel dataset
        model_output_path: Path untuk menyimpan model yang sudah ditrain
        progress_file: Path ke file untuk menyimpan progress (optional)
    
    Returns:
        Dictionary dengan hasil training dan metrics
    """
    
    total_steps = 8
    current_step = 0
    
    output_progress(current_step, total_steps, "Memulai training model...", progress_file)
    
    print("=" * 60)
    print("E-SHRIMP : Random Forest Regression Training")
    print("=" * 60)
    
    # =======================
    # 1) Load Dataset
    # =======================
    current_step = 1
    output_progress(current_step, total_steps, "Memuat dataset...", progress_file)
    print("\n[1] Loading dataset...")
    try:
        df = pd.read_excel(dataset_path)
        print(f"[OK] Dataset loaded successfully!")
        print(f"  Shape: {df.shape}")
        print(f"  Columns: {df.columns.tolist()}")
    except FileNotFoundError:
        print(f"[ERROR] File '{dataset_path}' tidak ditemukan!")
        print(f"  Pastikan file Excel berada di: {os.path.abspath(dataset_path)}")
        return None
    except Exception as e:
        print(f"[ERROR] Error loading dataset: {str(e)}")
        return None
    
    # =======================
    # 2) Pilih fitur dan target
    # =======================
    current_step = 2
    output_progress(current_step, total_steps, "Mempersiapkan fitur dan target...", progress_file)
    print("\n[2] Preparing features and targets...")
    features = ["Umur_Budidaya", "pH", "Salinitas_ppt", "DO_mgL", "Suhu_C"]
    targets = [
        "Berat_udang_gr",
        "Laju_pertumbuhan_harian_gr",
        "Feed_Rate_persen",
        "Pakan_per_hari_kg",
        "Akumulasi_pakan_kg"
    ]
    
    # Check if all required columns exist
    missing_features = [f for f in features if f not in df.columns]
    missing_targets = [t for t in targets if t not in df.columns]
    
    if missing_features:
        print(f"[ERROR] Kolom fitur tidak ditemukan: {missing_features}")
        print(f"  Kolom yang tersedia: {df.columns.tolist()}")
        return None
    
    if missing_targets:
        print(f"[ERROR] Kolom target tidak ditemukan: {missing_targets}")
        print(f"  Kolom yang tersedia: {df.columns.tolist()}")
        return None
    
    # Select and clean data
    df_model = df[features + targets].dropna().reset_index(drop=True)
    print(f"[OK] Data prepared: {len(df_model)} rows after removing NaN")
    
    if len(df_model) == 0:
        print("[ERROR] Tidak ada data yang valid setelah menghapus NaN")
        return None
    
    # =======================
    # 3) Siapkan X dan y
    # =======================
    current_step = 3
    output_progress(current_step, total_steps, "Mempersiapkan data training...", progress_file)
    print("\n[3] Preparing X and y...")
    X = df_model[features].astype(float)
    y = df_model[targets].astype(float)
    print(f"[OK] X shape: {X.shape}, y shape: {y.shape}")
    
    # =======================
    # 4) Train-Test Split
    # =======================
    current_step = 4
    output_progress(current_step, total_steps, "Membagi data training dan testing...", progress_file)
    print("\n[4] Splitting data into train and test sets...")
    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=42
    )
    print(f"[OK] Train shape: {X_train.shape}")
    print(f"[OK] Test shape: {X_test.shape}")
    
    # =======================
    # 5) Random Forest Multi-output
    # =======================
    current_step = 5
    output_progress(current_step, total_steps, "Melatih model Random Forest... (ini mungkin memakan waktu beberapa detik)", progress_file)
    print("\n[5] Training Random Forest model...")
    print("  This may take a few seconds...")
    
    # Reduce n_estimators untuk training lebih cepat (dari 300 ke 100)
    # Ini masih cukup akurat untuk prediksi
    rf = MultiOutputRegressor(
        RandomForestRegressor(
            n_estimators=100,  # Reduced from 300 for faster training
            random_state=42,
            n_jobs=-1,
            max_depth=10  # Limit depth for faster training
        )
    )
    
    rf.fit(X_train, y_train)
    current_step = 6
    output_progress(current_step, total_steps, "Model training selesai, mengevaluasi performa...", progress_file)
    print("[OK] Model training completed!")
    
    # =======================
    # 6) Evaluasi Per Target
    # =======================
    current_step = 6
    output_progress(current_step, total_steps, "Mengevaluasi performa model...", progress_file)
    print("\n[6] Evaluating model performance...")
    y_pred_test = rf.predict(X_test)
    y_pred_train = rf.predict(X_train)
    
    def metrics_per_target(y_true, y_pred, target_names):
        res = {}
        for i, name in enumerate(target_names):
            yt = y_true.iloc[:, i].values
            yp = y_pred[:, i]
            mae = mean_absolute_error(yt, yp)
            rmse = np.sqrt(mean_squared_error(yt, yp))
            r2 = r2_score(yt, yp)
            res[name] = {"MAE": mae, "RMSE": rmse, "R2": r2}
        return pd.DataFrame(res).T
    
    print("\n" + "=" * 60)
    print("Accuracy on TEST Data:")
    print("=" * 60)
    test_metrics = metrics_per_target(y_test, y_pred_test, targets)
    print(test_metrics)
    
    print("\n" + "=" * 60)
    print("Accuracy on TRAIN Data:")
    print("=" * 60)
    train_metrics = metrics_per_target(y_train, y_pred_train, targets)
    print(train_metrics)
    
    # =======================
    # 7) Check Overfitting
    # =======================
    print("\n" + "=" * 60)
    print("OVERFITTING CHECK:")
    print("=" * 60)
    overfitting_warnings = []
    for i, name in enumerate(targets):
        r2_train = r2_score(y_train.iloc[:, i], y_pred_train[:, i])
        r2_test = r2_score(y_test.iloc[:, i], y_pred_test[:, i])
        diff = r2_train - r2_test
        
        print(f"\nTarget: {name}")
        print(f"  R2 Train: {r2_train:.4f}")
        print(f"  R2 Test : {r2_test:.4f}")
        print(f"  Difference: {diff:.4f}")
        
        if diff > 0.15:
            print("  [WARNING] Overfitting terdeteksi")
            overfitting_warnings.append(name)
        elif r2_test < 0.4:
            print("  [WARNING] Underfitting (model kurang belajar)")
            overfitting_warnings.append(name)
        else:
            print("  [OK] Model sehat")
    
    # =======================
    # 8) Save Model
    # =======================
    current_step = 7
    output_progress(current_step, total_steps, "Menyimpan model...", progress_file)
    print("\n[8] Saving model...")
    try:
        # Get absolute path
        script_dir = os.path.dirname(os.path.abspath(__file__))
        model_path = os.path.join(script_dir, model_output_path)
        
        joblib.dump(rf, model_path)
        print(f"[OK] Model saved to: {model_path}")
        
        # Verify model can be loaded
        test_model = joblib.load(model_path)
        print("[OK] Model verification: Successfully loaded")
        
    except Exception as e:
        print(f"[ERROR] Error saving model: {str(e)}")
        return None
    
    # =======================
    # 9) Summary
    # =======================
    print("\n" + "=" * 60)
    print("TRAINING SUMMARY")
    print("=" * 60)
    print(f"Dataset: {dataset_path}")
    print(f"Total samples: {len(df_model)}")
    print(f"Train samples: {len(X_train)}")
    print(f"Test samples: {len(X_test)}")
    print(f"Model saved: {model_path}")
    
    if overfitting_warnings:
        print(f"\n[WARNING] Warnings: {len(overfitting_warnings)} target(s) may need attention")
        print(f"  Targets: {', '.join(overfitting_warnings)}")
    else:
        print("\n[OK] All targets show healthy model performance")
    
    current_step = 8
    output_progress(current_step, total_steps, "Training selesai!", progress_file)
    print("\n" + "=" * 60)
    print("Training completed successfully!")
    print("=" * 60)
    
    return {
        'success': True,
        'model_path': model_path,
        'train_metrics': train_metrics.to_dict(),
        'test_metrics': test_metrics.to_dict(),
        'overfitting_warnings': overfitting_warnings
    }

if __name__ == '__main__':
    # Get dataset path from command line or use default
    if len(sys.argv) > 1:
        dataset_path = sys.argv[1]
    else:
        # Default: look for dataset in parent directory
        script_dir = os.path.dirname(os.path.abspath(__file__))
        dataset_path = os.path.join(os.path.dirname(script_dir), 'dataset_e-shrimp.xlsx')
    
    # Get model output path from command line or use default
    if len(sys.argv) > 2:
        model_output_path = sys.argv[2]
    else:
        model_output_path = 'random_forest_multi.pkl'
    
    # Get progress file path (optional, 4th argument)
    progress_file = None
    if len(sys.argv) > 3:
        progress_file = sys.argv[3]
    
    result = train_model(dataset_path, model_output_path, progress_file)
    
    if result is None:
        sys.exit(1)
    else:
        sys.exit(0)

