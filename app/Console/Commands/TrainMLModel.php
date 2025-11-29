<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TrainMLModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ml:train';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Train the ML model for growth prediction';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting ML model training...');
        
        $modelPath = base_path('ml_models/random_forest_multi.pkl');
        $datasetPath = base_path('dataset_e-shrimp.xlsx');
        $trainScript = base_path('ml_models/train_model.py');

        // Check if model already exists
        if (file_exists($modelPath)) {
            $this->warn('Model already exists at: ' . $modelPath);
            if (!$this->confirm('Do you want to retrain the model?', false)) {
                $this->info('Training cancelled.');
                return 0;
            }
            // Delete existing model
            unlink($modelPath);
            $this->info('Existing model deleted.');
        }

        // Check if dataset exists
        if (!file_exists($datasetPath)) {
            $this->error('Dataset file not found: ' . $datasetPath);
            return 1;
        }

        // Check if training script exists
        if (!file_exists($trainScript)) {
            $this->error('Training script not found: ' . $trainScript);
            return 1;
        }

        // Try python3 first, then python
        $pythonCmd = 'python3';
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $pythonCmd = 'python';
        }

        // Get absolute paths
        $trainScriptAbs = realpath($trainScript);
        $datasetPathAbs = realpath($datasetPath);
        $mlModelsDir = realpath(base_path('ml_models'));
        $requirementsPath = base_path('ml_models/requirements.txt');
        
        if (!$trainScriptAbs || !$datasetPathAbs || !$mlModelsDir) {
            $this->error('Failed to get absolute paths for training');
            return 1;
        }
        
        // Change to ml_models directory for training
        $originalDir = getcwd();
        chdir($mlModelsDir);
        
        // Check and install dependencies first
        $this->info('Checking Python dependencies...');
        $checkDepsCmd = $pythonCmd . " -c \"import pandas, numpy, sklearn, joblib, openpyxl\" 2>&1";
        $depsCheck = shell_exec($checkDepsCmd);
        
        if ($depsCheck && strpos($depsCheck, 'ModuleNotFoundError') !== false) {
            $this->info('Dependencies not found, installing...');
            if (file_exists($requirementsPath)) {
                $installCmd = $pythonCmd . " -m pip install -r " . escapeshellarg($requirementsPath) . " 2>&1";
                $installOutput = shell_exec($installCmd);
                $this->info('Dependencies installation output: ' . substr($installOutput ?: 'No output', 0, 500));
            } else {
                // Install manually if requirements.txt doesn't exist
                $installCmd = $pythonCmd . " -m pip install pandas numpy scikit-learn joblib openpyxl 2>&1";
                $installOutput = shell_exec($installCmd);
                $this->info('Dependencies installation output: ' . substr($installOutput ?: 'No output', 0, 500));
            }
        } else {
            $this->info('Dependencies already installed');
        }
        
        // Create progress file for real-time progress tracking
        $progressFile = storage_path('app/training_progress.json');
        
        // Run training script
        $command = $pythonCmd . " " . escapeshellarg($trainScriptAbs) . " " . escapeshellarg($datasetPathAbs) . " " . escapeshellarg('random_forest_multi.pkl') . " " . escapeshellarg($progressFile) . " 2>&1";
        
        $this->info('Running training command...');
        $this->info('Working directory: ' . getcwd());
        
        // Run training (blocking - we want to see the output)
        $output = shell_exec($command);
        
        // Restore original directory
        chdir($originalDir);
        
        // Check if model was created
        if (file_exists($modelPath)) {
            $this->info('✓ Training completed successfully!');
            $this->info('Model saved at: ' . $modelPath);
            
            // Display training output if available
            if ($output) {
                $this->line('Training output:');
                $this->line($output);
            }
            
            return 0;
        } else {
            $this->error('Training failed - model file not found');
            if ($output) {
                $this->error('Error output:');
                $this->error($output);
            }
            return 1;
        }
    }
}








