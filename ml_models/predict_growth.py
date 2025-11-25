#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
E-SHRIMP: Random Forest Prediction Script
Mengambil input dari command line dan mengembalikan prediksi dalam format JSON
"""

import sys
import json
import joblib
import pandas as pd
import numpy as np

def predict_from_input(input_json, model_path):
    """
    Memprediksi pertumbuhan udang berdasarkan input data
    
    Args:
        input_json: JSON string berisi input data
        model_path: Path ke model file (.pkl)
    
    Returns:
        Dictionary dengan prediksi
    """
    try:
        # Parse input JSON
        input_data = json.loads(input_json)
        
        # Load model
        model = joblib.load(model_path)
        
        # Prepare features sesuai dengan model training
        features = ["Umur_Budidaya", "pH", "Salinitas_ppt", "DO_mgL", "Suhu_C"]
        
        # Create DataFrame from input
        df_input = pd.DataFrame([[
            input_data.get('Umur_Budidaya', 0),
            input_data.get('pH', 0),
            input_data.get('Salinitas_ppt', 0),
            input_data.get('DO_mgL', 0),
            input_data.get('Suhu_C', 0),
        ]], columns=features)
        
        # Predict
        prediction = model.predict(df_input)[0]
        
        # Target names sesuai dengan model training
        target_names = [
            "Berat_udang_gr",
            "Laju_pertumbuhan_harian_gr",
            "Feed_Rate_persen",
            "Pakan_per_hari_kg",
            "Akumulasi_pakan_kg"
        ]
        
        # Create result dictionary
        result = {}
        for i, name in enumerate(target_names):
            result[name] = float(prediction[i])
        
        return {
            'success': True,
            'prediction': result
        }
        
    except Exception as e:
        return {
            'success': False,
            'error': str(e)
        }

if __name__ == '__main__':
    if len(sys.argv) < 3:
        print(json.dumps({
            'success': False,
            'error': 'Usage: python predict_growth.py <input_json> <model_path>'
        }))
        sys.exit(1)
    
    input_json = sys.argv[1]
    model_path = sys.argv[2]
    
    result = predict_from_input(input_json, model_path)
    print(json.dumps(result))





