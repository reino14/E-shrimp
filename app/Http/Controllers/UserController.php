<?php

namespace App\Http\Controllers;

use App\Models\DashboardMonitoring;
use App\Models\SensorData;
use App\Models\Threshold;
use App\Models\Notifikasi;
use App\Models\Artikel;
use App\Models\Forum;
use App\Models\Peternak;
use App\Models\RobotKapalEshrimp;
use App\Models\MonitoringSession;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard(Request $request)
    {
        // Get email from session (set by syncPeternak endpoint)
        $email = session('user_email');
        
        // If no email in session, we need to wait for sync from frontend
        // For now, create a temporary peternak to avoid foreign key error
        // This will be updated when sync completes
        if (!$email) {
            // Try to get from request query (fallback)
            $email = $request->query('email');
            
            // If still no email, use dummy but ensure it exists in DB
            if (!$email) {
                $email = 'dummy@example.com';
                // Ensure dummy peternak exists to avoid foreign key error
                Peternak::firstOrCreate(
                    ['email_peternak' => 'dummy@example.com'],
                    [
                        'nama' => 'Dummy Peternak',
                        'password' => '',
                        'role' => 'peternak',
                    ]
                );
            } else {
                // Email from query - ensure peternak exists
                Peternak::firstOrCreate(
                    ['email_peternak' => $email],
                    [
                        'nama' => 'Peternak ' . $email,
                        'password' => '',
                        'role' => 'peternak',
                    ]
                );
            }
        } else {
            // Email from session - ensure peternak exists
            Peternak::firstOrCreate(
                ['email_peternak' => $email],
                [
                    'nama' => 'Peternak ' . $email,
                    'password' => '',
                    'role' => 'peternak',
                ]
            );
        }
        
        // Get kolam for the peternak
        // Use firstOrNew to avoid creating if email is dummy and will be updated
        $kolam = DashboardMonitoring::firstOrNew(
            ['kolam_id' => 'KOLAM-001']
        );
        
        // Only set email if it's not dummy, or if kolam doesn't exist yet
        if ($email !== 'dummy@example.com' || !$kolam->exists) {
            $kolam->email_peternak = $email;
            $kolam->save();
        } else if ($kolam->email_peternak === 'dummy@example.com' && $email !== 'dummy@example.com') {
            // Update if email changed from dummy to real
            $kolam->email_peternak = $email;
            $kolam->save();
        }

        // Get latest sensor data
        $latestData = SensorData::latest('waktu')->first();
        
        // Get thresholds
        $thresholds = Threshold::where('kolam_id', $kolam->kolam_id)->get();
        
        // Get unread notifications
        $notifikasis = Notifikasi::where('kolam_id', $kolam->kolam_id)
            ->where('status', false)
            ->latest('waktu')
            ->take(5)
            ->get();

        // Get historical data for charts (last 24 hours) - empty collection if no data
        $historicalData = collect();

        return view('dashboard', compact('kolam', 'latestData', 'thresholds', 'notifikasis', 'historicalData'));
    }

    public function historiData(Request $request)
    {
        $perPage = $request->get('per_page', 50);
        $perPage = in_array($perPage, [10, 50, 100]) ? $perPage : 50;
        
        $historicalData = SensorData::with('robotKapal')
            ->latest('waktu')
            ->paginate($perPage);
        $historicalData->appends($request->query());
        
        return view('user.histori-data', compact('historicalData'));
    }

    public function aturThreshold(Request $request)
    {
        $request->validate([
            'kolam_id' => 'required',
            'sensor_tipe' => 'required',
            'nilai' => 'required|numeric',
        ]);

        // Save to Laravel database (for backup/sync)
        Threshold::updateOrCreate(
            [
                'kolam_id' => $request->kolam_id,
                'sensor_tipe' => $request->sensor_tipe,
            ],
            [
                'nilai' => $request->nilai,
                'timer' => $request->timer ?? now(),
            ]
        );

        // Note: Threshold will also be saved to Firebase via frontend JavaScript
        // The frontend will call setThreshold() from firebase-rtdb.js

        return redirect()->route('dashboard')->with('success', 'Threshold berhasil diatur');
    }

    public function bacaArtikel()
    {
        $artikels = Artikel::latest('tanggal')->paginate(10);
        return view('user.artikel', compact('artikels'));
    }

    public function detailArtikel($id)
    {
        $artikel = Artikel::where('artikel_id', $id)->firstOrFail();
        return view('user.detail-artikel', compact('artikel'));
    }

    public function forum()
    {
        $forums = Forum::with('peternak')->latest('tanggal')->paginate(10);
        return view('user.forum', compact('forums'));
    }

    public function postForum(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
        ]);

        Forum::create([
            'forum_id' => 'FORUM' . time(),
            'judul' => $request->judul,
            'isi' => $request->isi,
            'tanggal' => now(),
            'email_peternak' => 'dummy@example.com', // In real app, get from auth
        ]);

        return redirect()->route('user.forum')->with('success', 'Post berhasil ditambahkan');
    }

    public function replyForum(Request $request, $id)
    {
        $request->validate([
            'isi' => 'required',
        ]);

        Forum::create([
            'forum_id' => 'FORUM' . time(),
            'judul' => 'Re: ' . Forum::find($id)->judul,
            'isi' => $request->isi,
            'tanggal' => now(),
            'post_peternak_id' => $id,
            'email_peternak' => 'dummy@example.com',
        ]);

        return redirect()->route('user.forum')->with('success', 'Reply berhasil ditambahkan');
    }

    public function prediksiPertumbuhan()
    {
        $email = session('user_email', 'dummy@example.com');
        
        // Get all kolams for the user
        $kolams = DashboardMonitoring::where('email_peternak', $email)->get();
        
        // Find eligible kolams (those with monitoring sessions >= day 14)
        $eligibleKolams = [];
        
        foreach ($kolams as $kolam) {
            // Get all monitoring sessions for this kolam
            $sessions = MonitoringSession::where('kolam_id', $kolam->kolam_id)->get();
            
            // Find the maximum umur_budidaya from all sessions
            $maxUmur = $sessions->max('umur_budidaya') ?? 0;
            
            // Get the session with max umur to get nama_kapal
            $maxSession = $sessions->where('umur_budidaya', $maxUmur)->first();
            
            // Only include if umur_budidaya >= 14
            if ($maxUmur >= 14) {
                $eligibleKolams[] = [
                    'kolam_id' => $kolam->kolam_id,
                    'max_umur' => $maxUmur,
                    'nama_kapal' => $maxSession->nama_kapal ?? '-',
                ];
            }
        }
        
        // Can predict if there's at least one eligible kolam
        $canPredict = count($eligibleKolams) > 0;
        
        // Dummy prediction data (will be replaced by actual prediction)
        $prediksi = [
            'berat_estimasi' => 25.5,
            'panjang_estimasi' => 12.3,
            'kebutuhan_pakan' => 150,
            'tanggal_panen_estimasi' => now()->addDays(30),
        ];
        
        return view('user.prediksi-pertumbuhan', compact('canPredict', 'eligibleKolams', 'prediksi'));
    }

    public function predictGrowth(Request $request)
    {
        $request->validate([
            'kolam_id' => 'required|string',
        ]);

        $email = session('user_email', 'dummy@example.com');
        $kolamId = $request->kolam_id;

        // Verify kolam belongs to user
        $kolam = DashboardMonitoring::where('kolam_id', $kolamId)
            ->where('email_peternak', $email)
            ->firstOrFail();

        // Get the latest monitoring session for this kolam with max umur_budidaya
        $sessions = MonitoringSession::where('kolam_id', $kolamId)->get();
        $maxUmur = $sessions->max('umur_budidaya') ?? 0;

        if ($maxUmur < 14) {
            return response()->json([
                'success' => false,
                'message' => 'Kolam harus memiliki monitoring dengan umur budidaya minimal hari ke-14',
            ], 400);
        }

        // Get latest sensor data for this kolam and umur_budidaya
        $latestSensorData = SensorData::where('kolam_id', $kolamId)
            ->where('umur_budidaya', $maxUmur)
            ->latest('waktu')
            ->first();

        if (!$latestSensorData) {
            return response()->json([
                'success' => false,
                'message' => 'Data sensor tidak ditemukan untuk kolam ini',
            ], 404);
        }

        // Prepare input data for prediction
        $inputData = [
            'Umur_Budidaya' => $maxUmur,
            'pH' => $latestSensorData->ph ?? 7.5,
            'Salinitas_ppt' => $latestSensorData->salinitas ?? 20.0,
            'DO_mgL' => $latestSensorData->oksigen ?? 6.0,
            'Suhu_C' => $latestSensorData->suhu ?? 30.0,
        ];

        // Try to call Python script if available, otherwise use simple calculation
        $prediction = $this->runPrediction($inputData);

        return response()->json([
            'success' => true,
            'prediction' => $prediction,
        ]);
    }

    private function runPrediction($inputData)
    {
        // Path to Python script
        $pythonScript = base_path('ml_models/predict_growth.py');
        // Use the correct model name that matches the training output
        $modelPath = base_path('ml_models/random_forest_multi.pkl');

        // Check if Python script and model exist
        if (file_exists($pythonScript) && file_exists($modelPath)) {
            try {
                // Prepare JSON input
                $inputJson = json_encode($inputData);
                
                // Execute Python script (use python3 on Linux/Mac, python on Windows)
                $pythonCmd = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'python' : 'python3';
                $command = escapeshellcmd($pythonCmd) . " " . escapeshellarg($pythonScript) . " " . escapeshellarg($inputJson) . " " . escapeshellarg($modelPath) . " 2>&1";
                $output = shell_exec($command);
                
                if ($output) {
                    $result = json_decode($output, true);
                    if ($result && isset($result['success']) && $result['success']) {
                        return $result['prediction'];
                    }
                }
            } catch (\Exception $e) {
                // Fall through to simple calculation
            }
        }

        // Simple prediction calculation (fallback if Python script is not available)
        // These are simplified formulas based on typical shrimp growth patterns
        $umur = $inputData['Umur_Budidaya'];
        $ph = $inputData['pH'];
        $salinitas = $inputData['Salinitas_ppt'];
        $do = $inputData['DO_mgL'];
        $suhu = $inputData['Suhu_C'];

        // Simple growth estimation formulas
        // Berat udang (gram) - simplified growth curve
        $berat = 0.1 * pow($umur, 1.5) * (1 + ($suhu - 28) * 0.05) * (1 + ($do - 5) * 0.1);
        
        // Laju pertumbuhan harian (gram/hari)
        $laju = 0.15 * pow($umur, 0.5) * (1 + ($suhu - 28) * 0.03);
        
        // Feed Rate (%)
        $feedRate = max(3, 10 - ($umur / 10)) * (1 + ($suhu - 28) * 0.02);
        
        // Pakan per hari (kg) - assuming 10,000 shrimps per kolam
        $jumlahUdang = 10000;
        $pakanHari = ($berat * $jumlahUdang * $feedRate) / 100000; // Convert to kg
        
        // Akumulasi pakan (kg)
        $akumulasi = $pakanHari * $umur * 0.8; // 0.8 is efficiency factor

        return [
            'Berat_udang_gr' => round($berat, 2),
            'Laju_pertumbuhan_harian_gr' => round($laju, 2),
            'Feed_Rate_persen' => round($feedRate, 2),
            'Pakan_per_hari_kg' => round($pakanHari, 2),
            'Akumulasi_pakan_kg' => round($akumulasi, 2),
        ];
    }

    public function getTrainingProgress()
    {
        // Return dummy training progress (if needed in the future)
        return response()->json([
            'success' => true,
            'progress' => 100,
            'status' => 'completed',
        ]);
    }

    public function profil(Request $request)
    {
        $email = session('user_email', 'dummy@example.com');
        $peternak = null;
        
        if ($email && $email !== 'dummy@example.com') {
            $peternak = Peternak::where('email_peternak', $email)->first();
        }
        
        // If AJAX request (for modal), return JSON
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            if ($peternak) {
                return response()->json([
                    'success' => true,
                    'peternak' => [
                        'nama' => $peternak->nama ?? '-',
                        'email' => $peternak->email_peternak ?? '-',
                        'role' => $peternak->role ?? 'Peternak',
                        'tracker_id' => $peternak->tracker_id ?? null,
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data profil tidak ditemukan. Silakan login kembali.'
                ], 404);
            }
        }
        
        // Otherwise return view
        return view('user.profil', compact('peternak'));
    }

    public function priceTracker()
    {
        // Dummy price data - in production, this would come from an API or database
        $hargaUdang = [
            ['ukuran' => 'Size 100 (10g)', 'harga' => 45000, 'perubahan' => '+2.3%', 'trend' => 'up'],
            ['ukuran' => 'Size 80 (12.5g)', 'harga' => 52000, 'perubahan' => '+1.8%', 'trend' => 'up'],
            ['ukuran' => 'Size 60 (16.7g)', 'harga' => 58000, 'perubahan' => '-0.5%', 'trend' => 'down'],
            ['ukuran' => 'Size 50 (20g)', 'harga' => 65000, 'perubahan' => '+3.1%', 'trend' => 'up'],
            ['ukuran' => 'Size 40 (25g)', 'harga' => 72000, 'perubahan' => '+1.2%', 'trend' => 'up'],
            ['ukuran' => 'Size 30 (33.3g)', 'harga' => 85000, 'perubahan' => '+4.5%', 'trend' => 'up'],
        ];
        
        return view('user.price-tracker', compact('hargaUdang'));
    }


    /**
     * Sync peternak from Firebase RDTB to Laravel DB
     * Called from frontend after login
     */
    public function syncPeternak(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'nama' => 'nullable|string',
        ]);

        $email = $request->email;
        $nama = $request->nama ?? 'Peternak ' . $email;

        // Create or update peternak in Laravel DB
        $peternak = Peternak::updateOrCreate(
            ['email_peternak' => $email],
            [
                'nama' => $nama,
                'password' => '', // Password stored in Firebase RDTB only
                'role' => 'peternak',
            ]
        );

        // Store email in session for subsequent requests
        session(['user_email' => $email]);

        return response()->json([
            'success' => true,
            'message' => 'Peternak berhasil disinkronkan',
            'peternak' => $peternak,
        ]);
    }

    public function manajemenKapal()
    {
        $email = session('user_email', 'dummy@example.com');
        
        $kapals = RobotKapalEshrimp::where('email_peternak', $email)->get();
        
        // Get stats for each kapal
        $kapalStats = [];
        $kapalActiveSessions = [];
        $kapalMonitoredCombinations = [];
        
        foreach ($kapals as $kapal) {
            $sessions = MonitoringSession::where('nama_kapal', $kapal->robot_id)
                ->where('is_active', true)
                ->get();
            
            $kapalActiveSessions[$kapal->robot_id] = $sessions;
            
            $kapalStats[$kapal->robot_id] = [
                'active_sessions' => $sessions->count(),
                'total_sessions' => MonitoringSession::where('nama_kapal', $kapal->robot_id)->count(),
            ];
            
            // Get monitored combinations for validation
            $allSessions = MonitoringSession::where('nama_kapal', $kapal->robot_id)->get();
            $combinations = [];
            foreach ($allSessions as $session) {
                $key = $session->kolam_id . '-' . $session->umur_budidaya;
                $combinations[$key] = true;
            }
            $kapalMonitoredCombinations[$kapal->robot_id] = $combinations;
        }
        
        // Get available kolams for the peternak
        $availableKolams = DashboardMonitoring::where('email_peternak', $email)->get();
        
        return view('user.manajemen-kapal', compact(
            'kapals',
            'kapalStats',
            'kapalActiveSessions',
            'kapalMonitoredCombinations',
            'availableKolams'
        ));
    }

    public function tambahKapal(Request $request)
    {
        $request->validate([
            'robot_id' => 'required|string|unique:robot_kapal_eshrimps,robot_id',
            'lokasi' => 'nullable|string',
        ]);

        $email = session('user_email', 'dummy@example.com');

        RobotKapalEshrimp::create([
            'robot_id' => $request->robot_id,
            'email_peternak' => $email,
            'status' => 'idle',
            'lokasi' => $request->lokasi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kapal berhasil ditambahkan',
        ]);
    }

    public function updateKapal(Request $request, $robotId)
    {
        $request->validate([
            'lokasi' => 'nullable|string',
            'status' => 'required|in:idle,active,maintenance',
        ]);

        $email = session('user_email', 'dummy@example.com');
        
        $kapal = RobotKapalEshrimp::where('robot_id', $robotId)
            ->where('email_peternak', $email)
            ->firstOrFail();

        $kapal->update([
            'lokasi' => $request->lokasi,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kapal berhasil diperbarui',
        ]);
    }

    public function hapusKapal($robotId)
    {
        $email = session('user_email', 'dummy@example.com');
        
        $kapal = RobotKapalEshrimp::where('robot_id', $robotId)
            ->where('email_peternak', $email)
            ->firstOrFail();

        // Check if kapal has active monitoring sessions
        $activeSessions = MonitoringSession::where('nama_kapal', $robotId)
            ->where('is_active', true)
            ->count();

        if ($activeSessions > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kapal tidak dapat dihapus karena memiliki monitoring session aktif',
            ], 400);
        }

        $kapal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kapal berhasil dihapus',
        ]);
    }

    public function manajemenKolam()
    {
        $email = session('user_email', 'dummy@example.com');
        
        $kolams = DashboardMonitoring::where('email_peternak', $email)->get();
        
        // Get stats and history for each kolam
        $kolamStats = [];
        $kolamMonitoringHistory = [];
        
        foreach ($kolams as $kolam) {
            $sessions = MonitoringSession::where('kolam_id', $kolam->kolam_id)->get();
            $activeSessions = $sessions->where('is_active', true);
            
            // Get monitored days (unique umur_budidaya values)
            $monitoredDays = $sessions->pluck('umur_budidaya')->unique()->sort()->values()->toArray();
            
            // Get history with average values for completed sessions
            $history = $sessions->map(function ($session) {
                if (!$session->is_active) {
                    // Calculate averages from sensor data for completed sessions
                    $sensorData = SensorData::where('kolam_id', $session->kolam_id)
                        ->where('umur_budidaya', $session->umur_budidaya)
                        ->whereBetween('waktu', [
                            $session->mulai_monitoring,
                            $session->selesai_monitoring ?? now()
                        ])
                        ->get();
                    
                    if ($sensorData->count() > 0) {
                        $session->avg_ph = $sensorData->avg('ph');
                        $session->avg_suhu = $sensorData->avg('suhu');
                        $session->avg_oksigen = $sensorData->avg('oksigen');
                        $session->avg_salinitas = $sensorData->avg('salinitas');
                    }
                }
                return $session;
            })->sortByDesc('mulai_monitoring');
            
            $kolamStats[$kolam->kolam_id] = [
                'active_sessions' => $activeSessions->count(),
                'total_sessions' => $sessions->count(),
                'monitored_days' => $monitoredDays,
            ];
            
            $kolamMonitoringHistory[$kolam->kolam_id] = $history;
        }
        
        return view('user.manajemen-kolam', compact('kolams', 'kolamStats', 'kolamMonitoringHistory'));
    }

    public function tambahKolam(Request $request)
    {
        $request->validate([
            'kolam_id' => 'required|string|unique:dashboard_monitorings,kolam_id',
        ]);

        $email = session('user_email', 'dummy@example.com');

        DashboardMonitoring::create([
            'kolam_id' => $request->kolam_id,
            'email_peternak' => $email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kolam berhasil ditambahkan',
        ]);
    }

    public function hapusKolam($kolamId)
    {
        $email = session('user_email', 'dummy@example.com');
        
        $kolam = DashboardMonitoring::where('kolam_id', $kolamId)
            ->where('email_peternak', $email)
            ->firstOrFail();

        // Check if kolam has active monitoring sessions
        $activeSessions = MonitoringSession::where('kolam_id', $kolamId)
            ->where('is_active', true)
            ->count();

        if ($activeSessions > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kolam tidak dapat dihapus karena memiliki monitoring session aktif',
            ], 400);
        }

        $kolam->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kolam berhasil dihapus',
        ]);
    }
}

