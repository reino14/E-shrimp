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
use App\Services\PriceTrackerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
        
        // Get available kapals for this user
        $availableKapals = RobotKapalEshrimp::where('email_peternak', $email)
            ->pluck('robot_id')
            ->toArray();
        
        // Handle kapal selection
        $selectedKapal = $request->query('nama_kapal') ?? session('selected_kapal');
        
        // Show kapal selection if:
        // 1. User explicitly requests to change kapal (change_kapal=1)
        // 2. No kapal is selected AND user has available kapals
        $showKapalSelection = false;
        if ($request->query('change_kapal')) {
            $showKapalSelection = true;
        } elseif (!$selectedKapal && count($availableKapals) > 0) {
            $showKapalSelection = true;
        }
        
        // If user selected a kapal, save it to session
        if ($request->query('nama_kapal') && in_array($request->query('nama_kapal'), $availableKapals)) {
            session(['selected_kapal' => $request->query('nama_kapal')]);
            $selectedKapal = $request->query('nama_kapal');
            $showKapalSelection = false;
        }
        
        // If showing kapal selection, return early with selection form
        if ($showKapalSelection) {
            return view('dashboard', compact('showKapalSelection', 'availableKapals'));
        }
        
        // Get monitoring sessions for selected kapal (or all active sessions if no kapal selected)
        $monitoringSessions = collect();
        if ($selectedKapal) {
            $monitoringSessions = MonitoringSession::where('nama_kapal', $selectedKapal)
                ->where('is_active', true)
                ->get();
        } else {
            // If no kapal selected, get all active sessions for this user's kapals
            if (count($availableKapals) > 0) {
                $monitoringSessions = MonitoringSession::whereIn('nama_kapal', $availableKapals)
                    ->where('is_active', true)
                    ->get();
            }
        }
        
        // Get kolam from first active monitoring session, or use default
        $kolam = null;
        if ($monitoringSessions->count() > 0) {
            $firstSession = $monitoringSessions->first();
            $kolam = DashboardMonitoring::where('kolam_id', $firstSession->kolam_id)->first();
        }
        
        // Fallback to default kolam if no active sessions
        if (!$kolam) {
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
        }

        // Get latest sensor data for the kolam
        $latestData = null;
        if ($kolam) {
            $latestData = SensorData::where('kolam_id', $kolam->kolam_id)
                ->latest('waktu')
                ->first();
        }
        
        // Get thresholds
        $thresholds = collect();
        if ($kolam) {
            $thresholds = Threshold::where('kolam_id', $kolam->kolam_id)->get();
        }
        
        // Get unread notifications
        $notifikasis = collect();
        if ($kolam) {
            $notifikasis = Notifikasi::where('kolam_id', $kolam->kolam_id)
                ->where('status', false)
                ->latest('waktu')
                ->take(5)
                ->get();
        }

        // Get historical data for charts (last 24 hours) - empty collection if no data
        $historicalData = collect();

        return view('dashboard', compact(
            'kolam', 
            'latestData', 
            'thresholds', 
            'notifikasis', 
            'historicalData',
            'monitoringSessions',
            'selectedKapal',
            'availableKapals',
            'showKapalSelection'
        ));
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

    public function priceTracker(Request $request)
    {
        $forceRefresh = $request->get('refresh') === '1';
        
        // Try to get from cache first (unless force refresh)
        if (!$forceRefresh) {
            $cachedData = Cache::get('harga_udang_vaname');
            if ($cachedData) {
                return view('user.price-tracker', [
                    'hargaUdang' => $cachedData['data'],
                    'lastUpdate' => $cachedData['last_update'],
                    'source' => $cachedData['source'] ?? 'Cache',
                ]);
            }
        }
        
        // Fetch fresh data using service
        $service = new PriceTrackerService();
        $hargaUdang = $service->fetchHargaUdang();
        
        // Cache for 6 hours
        Cache::put('harga_udang_vaname', [
            'data' => $hargaUdang,
            'last_update' => now(),
            'source' => 'API',
        ], now()->addHours(6));
        
        return view('user.price-tracker', [
            'hargaUdang' => $hargaUdang,
            'lastUpdate' => now(),
            'source' => 'API',
        ]);
    }

    public function refreshPriceTracker()
    {
        // Clear cache and fetch fresh data
        Cache::forget('harga_udang_vaname');
        
        $service = new PriceTrackerService();
        $hargaUdang = $service->fetchHargaUdang();
        
        // Cache for 6 hours
        Cache::put('harga_udang_vaname', [
            'data' => $hargaUdang,
            'last_update' => now(),
            'source' => 'API',
        ], now()->addHours(6));
        
        return response()->json([
            'success' => true,
            'message' => 'Data harga berhasil diperbarui',
            'data' => $hargaUdang,
            'last_update' => now()->format('d M Y H:i'),
        ]);
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
            
            // Auto-stop sessions that have exceeded 24 hours
            foreach ($sessions as $session) {
                $maxDuration = 86400; // 24 hours in seconds
                $startTime = $session->mulai_monitoring;
                
                if ($startTime) {
                    $elapsedSeconds = now()->diffInSeconds($startTime);
                    
                    if ($elapsedSeconds >= $maxDuration) {
                        // Auto-stop the session
                        $session->update([
                            'is_active' => false,
                            'selesai_monitoring' => now(),
                        ]);
                        
                        // Update kapal status if no other active sessions
                        $otherActiveSessions = MonitoringSession::where('nama_kapal', $kapal->robot_id)
                            ->where('is_active', true)
                            ->where('session_id', '!=', $session->session_id)
                            ->count();
                        
                        if ($otherActiveSessions == 0) {
                            $kapal->update(['status' => 'idle']);
                        }
                    }
                }
            }
            
            // Get active sessions again after auto-stop
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
        
        // Get next valid umur_budidaya for each kolam
        $kolamNextUmur = [];
        $validUmurSequence = [1, 7, 14, 21, 28, 35, 42, 49, 56, 63, 70, 77, 84, 91, 98];
        
        foreach ($availableKolams as $kolam) {
            $allSessionsForKolam = MonitoringSession::where('kolam_id', $kolam->kolam_id)
                ->orderBy('umur_budidaya', 'desc')
                ->get();
            
            if ($allSessionsForKolam->count() > 0) {
                $maxCompletedUmur = $allSessionsForKolam->max('umur_budidaya');
                $currentIndex = array_search($maxCompletedUmur, $validUmurSequence);
                
                if ($currentIndex !== false && $currentIndex < count($validUmurSequence) - 1) {
                    $kolamNextUmur[$kolam->kolam_id] = $validUmurSequence[$currentIndex + 1];
                } else {
                    $kolamNextUmur[$kolam->kolam_id] = null; // All completed
                }
            } else {
                $kolamNextUmur[$kolam->kolam_id] = 1; // Start from day 1
            }
        }
        
        return view('user.manajemen-kapal', compact(
            'kapals',
            'kapalStats',
            'kapalActiveSessions',
            'kapalMonitoredCombinations',
            'availableKolams',
            'kolamNextUmur'
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

    public function mulaiMonitoring(Request $request)
    {
        $request->validate([
            'nama_kapal' => 'required|string',
            'kolam_id' => 'required|string',
            'umur_budidaya' => 'required|integer',
            'threshold_suhu_min' => 'nullable|numeric',
            'threshold_suhu_max' => 'nullable|numeric',
            'threshold_ph_min' => 'nullable|numeric',
            'threshold_ph_max' => 'nullable|numeric',
            'threshold_oksigen_min' => 'nullable|numeric',
            'threshold_oksigen_max' => 'nullable|numeric',
            'threshold_salinitas_min' => 'nullable|numeric',
            'threshold_salinitas_max' => 'nullable|numeric',
            'timer_monitoring' => 'nullable|string',
        ]);

        $email = session('user_email', 'dummy@example.com');
        $namaKapal = $request->nama_kapal;
        $kolamId = $request->kolam_id;
        $umurBudidaya = $request->umur_budidaya;

        // Verify kapal exists and belongs to user
        $kapal = RobotKapalEshrimp::where('robot_id', $namaKapal)
            ->where('email_peternak', $email)
            ->first();

        if (!$kapal) {
            return response()->json([
                'success' => false,
                'message' => 'Kapal tidak ditemukan atau tidak memiliki akses',
            ], 404);
        }

        // Verify kolam exists and belongs to user
        $kolam = DashboardMonitoring::where('kolam_id', $kolamId)
            ->where('email_peternak', $email)
            ->first();

        if (!$kolam) {
            return response()->json([
                'success' => false,
                'message' => 'Kolam tidak ditemukan atau tidak memiliki akses',
            ], 404);
        }

        // Check if kapal already has an active monitoring session
        $activeSession = MonitoringSession::where('nama_kapal', $namaKapal)
            ->where('is_active', true)
            ->first();

        if ($activeSession) {
            return response()->json([
                'success' => false,
                'message' => 'Kapal sudah memiliki monitoring session aktif. Hentikan monitoring yang sedang berjalan terlebih dahulu.',
            ], 400);
        }

        // Validate sequential umur_budidaya for this kolam
        // Get all completed sessions for this kolam (both active and inactive)
        $allSessionsForKolam = MonitoringSession::where('kolam_id', $kolamId)
            ->orderBy('umur_budidaya', 'desc')
            ->get();

        // Define valid umur_budidaya sequence
        $validUmurSequence = [1, 7, 14, 21, 28, 35, 42, 49, 56, 63, 70, 77, 84, 91, 98];

        if ($allSessionsForKolam->count() > 0) {
            // Get the maximum umur_budidaya that has been completed for this kolam
            $maxCompletedUmur = $allSessionsForKolam->max('umur_budidaya');
            
            // Find the next valid umur_budidaya in sequence
            $currentIndex = array_search($maxCompletedUmur, $validUmurSequence);
            
            if ($currentIndex !== false && $currentIndex < count($validUmurSequence) - 1) {
                $nextValidUmur = $validUmurSequence[$currentIndex + 1];
                
                // Check if user is trying to skip days
                $requestedIndex = array_search($umurBudidaya, $validUmurSequence);
                
                if ($requestedIndex === false) {
                    return response()->json([
                        'success' => false,
                        'message' => "Umur budidaya hari ke-{$umurBudidaya} tidak valid. Pilih dari urutan yang tersedia.",
                    ], 400);
                }
                
                // User must continue from the next valid umur (cannot skip)
                if ($requestedIndex > $currentIndex + 1) {
                    return response()->json([
                        'success' => false,
                        'message' => "Anda tidak dapat melompati hari. Kolam {$kolamId} sudah selesai monitoring hingga hari ke-{$maxCompletedUmur}. Silakan lanjutkan dengan hari ke-{$nextValidUmur} terlebih dahulu.",
                    ], 400);
                }
                
                // User cannot go back to previous days
                if ($requestedIndex <= $currentIndex) {
                    return response()->json([
                        'success' => false,
                        'message' => "Kolam {$kolamId} sudah pernah monitoring hari ke-{$umurBudidaya}. Silakan lanjutkan dengan hari ke-{$nextValidUmur}.",
                    ], 400);
                }
            } else {
                // All days have been completed
                return response()->json([
                    'success' => false,
                    'message' => "Kolam {$kolamId} sudah menyelesaikan semua tahap monitoring.",
                ], 400);
            }
        } else {
            // First monitoring for this kolam - must start from day 1
            if ($umurBudidaya != 1) {
                return response()->json([
                    'success' => false,
                    'message' => "Monitoring untuk kolam {$kolamId} harus dimulai dari hari ke-1.",
                ], 400);
            }
        }

        // Prepare threshold data as JSON strings
        $thresholdSuhu = null;
        if ($request->threshold_suhu_min !== null || $request->threshold_suhu_max !== null) {
            $thresholdSuhu = json_encode([
                'min' => $request->threshold_suhu_min,
                'max' => $request->threshold_suhu_max,
            ]);
        }

        $thresholdPh = null;
        if ($request->threshold_ph_min !== null || $request->threshold_ph_max !== null) {
            $thresholdPh = json_encode([
                'min' => $request->threshold_ph_min,
                'max' => $request->threshold_ph_max,
            ]);
        }

        $thresholdOksigen = null;
        if ($request->threshold_oksigen_min !== null || $request->threshold_oksigen_max !== null) {
            $thresholdOksigen = json_encode([
                'min' => $request->threshold_oksigen_min,
                'max' => $request->threshold_oksigen_max,
            ]);
        }

        $thresholdSalinitas = null;
        if ($request->threshold_salinitas_min !== null || $request->threshold_salinitas_max !== null) {
            $thresholdSalinitas = json_encode([
                'min' => $request->threshold_salinitas_min,
                'max' => $request->threshold_salinitas_max,
            ]);
        }

        // Create monitoring session
        $session = MonitoringSession::create([
            'kolam_id' => $kolamId,
            'nama_kapal' => $namaKapal,
            'umur_budidaya' => $umurBudidaya,
            'threshold_suhu' => $thresholdSuhu,
            'threshold_ph' => $thresholdPh,
            'threshold_oksigen' => $thresholdOksigen,
            'threshold_salinitas' => $thresholdSalinitas,
            'timer_monitoring' => $request->timer_monitoring ?? '10',
            'mulai_monitoring' => now(),
            'is_active' => true,
            'is_paused' => false,
            'total_paused_seconds' => 0,
        ]);

        // Update kapal status to active
        $kapal->update(['status' => 'active']);

        return response()->json([
            'success' => true,
            'message' => 'Monitoring berhasil dimulai',
            'session_id' => $session->session_id,
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

    public function simulateRealtimeData(Request $request)
    {
        $request->validate([
            'kolam_id' => 'required|string',
            'umur_budidaya' => 'required|integer',
            'session_id' => 'required|integer',
        ]);

        $sessionId = $request->session_id;
        $kolamId = $request->kolam_id;
        $umurBudidaya = $request->umur_budidaya;

        // Get monitoring session
        $session = MonitoringSession::find($sessionId);
        
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Monitoring session tidak ditemukan',
            ], 404);
        }

        // Check if session is paused
        if ($session->is_paused) {
            return response()->json([
                'success' => false,
                'paused' => true,
                'message' => 'Monitoring session sedang di-pause',
            ]);
        }

        // Check if session is expired (24 hours)
        $startTime = $session->mulai_monitoring;
        $maxDuration = 86400; // 24 hours in seconds
        
        if ($startTime) {
            $totalElapsed = now()->diffInSeconds($startTime);
            $pausedSeconds = $session->total_paused_seconds ?? 0;
            $activeSeconds = max(0, $totalElapsed - $pausedSeconds);
            
            if ($activeSeconds >= $maxDuration) {
                // Mark session as inactive
                $session->update([
                    'is_active' => false,
                    'selesai_monitoring' => now(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'expired' => true,
                    'message' => 'Monitoring session telah berakhir (24 jam)',
                ]);
            }
        }

        // Check if session is still active
        if (!$session->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Monitoring session tidak aktif',
            ], 400);
        }

        // Get robot_id from session
        $robotId = $session->nama_kapal;

        // Generate realistic sensor data based on umur_budidaya
        // Base values change based on cultivation age
        $baseValues = $this->getBaseSensorValues($umurBudidaya);
        
        // Add some random variation (±5%)
        $sensorData = [
            'ph' => round($baseValues['ph'] + (rand(-50, 50) / 1000), 2),
            'suhu' => round($baseValues['suhu'] + (rand(-50, 50) / 100), 1),
            'oksigen' => round($baseValues['oksigen'] + (rand(-30, 30) / 100), 2),
            'salinitas' => round($baseValues['salinitas'] + (rand(-20, 20) / 100), 1),
        ];

        // Ensure values are within realistic ranges
        $sensorData['ph'] = max(6.5, min(9.0, $sensorData['ph']));
        $sensorData['suhu'] = max(24.0, min(35.0, $sensorData['suhu']));
        $sensorData['oksigen'] = max(4.0, min(10.0, $sensorData['oksigen']));
        $sensorData['salinitas'] = max(10.0, min(30.0, $sensorData['salinitas']));

        // Determine kualitas_air based on values
        $sensorData['kualitas_air'] = $this->determineKualitasAir($sensorData);

        // Save sensor data to database
        SensorData::create([
            'robot_id' => $robotId,
            'kolam_id' => $kolamId,
            'umur_budidaya' => $umurBudidaya,
            'waktu' => now(),
            'ph' => $sensorData['ph'],
            'suhu' => $sensorData['suhu'],
            'oksigen' => $sensorData['oksigen'],
            'salinitas' => $sensorData['salinitas'],
            'kualitas_air' => $sensorData['kualitas_air'],
        ]);

        // Check thresholds and create notifications if exceeded
        $notifications = $this->checkThresholdsAndNotify($session, $sensorData, $kolamId);

        return response()->json([
            'success' => true,
            'data' => array_merge($sensorData, [
                'waktu' => now()->toIso8601String(),
            ]),
            'notifications' => $notifications,
        ]);
    }

    private function getBaseSensorValues($umurBudidaya)
    {
        // Base values that change slightly based on cultivation age
        // Early days: more stable, optimal conditions
        // Later days: slight variations as shrimp grow
        
        $dayFactor = min($umurBudidaya / 100, 1.0); // Normalize to 0-1
        
        return [
            'ph' => 7.5 + ($dayFactor * 0.3), // 7.5 to 7.8
            'suhu' => 28.0 + ($dayFactor * 1.5), // 28 to 29.5
            'oksigen' => 6.5 - ($dayFactor * 0.5), // 6.5 to 6.0 (slightly decreases)
            'salinitas' => 15.0 + ($dayFactor * 3.0), // 15 to 18
        ];
    }

    private function determineKualitasAir($sensorData)
    {
        $ph = $sensorData['ph'];
        $suhu = $sensorData['suhu'];
        $oksigen = $sensorData['oksigen'];
        $salinitas = $sensorData['salinitas'];

        $score = 0;
        
        // pH check (optimal: 7.0-8.5)
        if ($ph >= 7.0 && $ph <= 8.5) {
            $score += 25;
        } elseif ($ph >= 6.5 && $ph < 7.0 || $ph > 8.5 && $ph <= 9.0) {
            $score += 15;
        } else {
            $score += 5;
        }

        // Suhu check (optimal: 26-32)
        if ($suhu >= 26 && $suhu <= 32) {
            $score += 25;
        } elseif ($suhu >= 24 && $suhu < 26 || $suhu > 32 && $suhu <= 35) {
            $score += 15;
        } else {
            $score += 5;
        }

        // Oksigen check (optimal: 5-8)
        if ($oksigen >= 5 && $oksigen <= 8) {
            $score += 25;
        } elseif ($oksigen >= 4 && $oksigen < 5 || $oksigen > 8 && $oksigen <= 10) {
            $score += 15;
        } else {
            $score += 5;
        }

        // Salinitas check (optimal: 15-25)
        if ($salinitas >= 15 && $salinitas <= 25) {
            $score += 25;
        } elseif ($salinitas >= 10 && $salinitas < 15 || $salinitas > 25 && $salinitas <= 30) {
            $score += 15;
        } else {
            $score += 5;
        }

        if ($score >= 90) return 'Sangat Baik';
        if ($score >= 70) return 'Baik';
        if ($score >= 50) return 'Cukup';
        return 'Buruk';
    }

    private function checkThresholdsAndNotify($session, $sensorData, $kolamId)
    {
        $notifications = [];

        // Get thresholds from session (stored as JSON strings)
        $thresholds = [
            'ph' => $this->parseThreshold($session->threshold_ph),
            'suhu' => $this->parseThreshold($session->threshold_suhu),
            'oksigen' => $this->parseThreshold($session->threshold_oksigen),
            'salinitas' => $this->parseThreshold($session->threshold_salinitas),
        ];

        // Check each sensor
        foreach ($thresholds as $sensorKey => $threshold) {
            if (!$threshold) continue;

            $value = $sensorData[$sensorKey];
            $min = $threshold['min'] ?? null;
            $max = $threshold['max'] ?? null;

            $exceeded = false;
            $message = '';

            if ($min !== null && $value < $min) {
                $exceeded = true;
                $message = ucfirst($sensorKey) . " ({$value}) di bawah batas minimal ({$min})";
            } elseif ($max !== null && $value > $max) {
                $exceeded = true;
                $message = ucfirst($sensorKey) . " ({$value}) melebihi batas maksimal ({$max})";
            }

            if ($exceeded) {
                // Create notification with kapal name
                $namaKapal = $session->nama_kapal ?? 'Unknown';
                $messageWithKapal = "[{$namaKapal}] " . $message;
                
                Notifikasi::create([
                    'kolam_id' => $kolamId,
                    'nama_kapal' => $namaKapal,
                    'pesan' => $messageWithKapal,
                    'waktu' => now(),
                    'status' => false, // unread
                ]);

                $notifications[] = [
                    'message' => $messageWithKapal,
                    'type' => $sensorKey,
                ];
            }
        }

        return $notifications;
    }

    private function parseThreshold($thresholdJson)
    {
        if (!$thresholdJson) return null;
        
        $decoded = json_decode($thresholdJson, true);
        if (!$decoded || !is_array($decoded)) return null;
        
        return [
            'min' => $decoded['min'] ?? null,
            'max' => $decoded['max'] ?? null,
        ];
    }

    public function getSensorData(Request $request)
    {
        $request->validate([
            'kolam_id' => 'required|string',
            'limit' => 'nullable|integer|max:100',
        ]);

        $limit = $request->get('limit', 20);
        $kolamId = $request->kolam_id;

        $sensorData = SensorData::where('kolam_id', $kolamId)
            ->latest('waktu')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'sensorData' => $sensorData,
        ]);
    }

    public function getThresholds(Request $request)
    {
        $request->validate([
            'kolam_id' => 'required|string',
        ]);

        $kolamId = $request->kolam_id;

        $thresholds = Threshold::where('kolam_id', $kolamId)->get();

        $formatted = [];
        foreach ($thresholds as $threshold) {
            $formatted[$threshold->sensor_tipe] = [
                'nilai' => $threshold->nilai,
                'updated_at' => $threshold->timer ?? $threshold->updated_at,
            ];
        }

        return response()->json([
            'success' => true,
            'thresholds' => $formatted,
        ]);
    }

    public function getNotifications(Request $request)
    {
        $kolamId = $request->get('kolam_id');
        
        $query = Notifikasi::query();
        
        if ($kolamId) {
            $query->where('kolam_id', $kolamId);
        }
        
        $notifications = $query->latest('waktu')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
        ]);
    }

    public function deleteNotification($notificationId)
    {
        $notification = Notifikasi::find($notificationId);
        
        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan',
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dihapus',
        ]);
    }

    public function clearAllNotifications(Request $request)
    {
        $kolamId = $request->get('kolam_id');
        
        $query = Notifikasi::query();
        
        if ($kolamId) {
            $query->where('kolam_id', $kolamId);
        }
        
        $query->delete();

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi berhasil dihapus',
        ]);
    }

    public function createNotification(Request $request)
    {
        $request->validate([
            'kolam_id' => 'required|string',
            'pesan' => 'required|string',
            'nama_kapal' => 'nullable|string',
        ]);

        $pesan = $request->pesan;
        if ($request->nama_kapal) {
            $pesan = "[{$request->nama_kapal}] " . $pesan;
        }

        $notification = Notifikasi::create([
            'kolam_id' => $request->kolam_id,
            'nama_kapal' => $request->nama_kapal,
            'pesan' => $pesan,
            'waktu' => now(),
            'status' => false, // unread
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil dibuat',
            'notification' => $notification,
        ]);
    }

    public function deleteMonitoringSession($sessionId)
    {
        $email = session('user_email', 'dummy@example.com');
        
        // Find the monitoring session
        $session = MonitoringSession::find($sessionId);
        
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Monitoring session tidak ditemukan',
            ], 404);
        }
        
        // Verify the session belongs to user's kapal
        $kapal = RobotKapalEshrimp::where('robot_id', $session->nama_kapal)
            ->where('email_peternak', $email)
            ->first();
        
        if (!$kapal) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus session ini',
            ], 403);
        }
        
        // Update session to inactive
        $session->update([
            'is_active' => false,
            'selesai_monitoring' => now(),
        ]);
        
        // Check if kapal has any other active sessions
        $otherActiveSessions = MonitoringSession::where('nama_kapal', $session->nama_kapal)
            ->where('is_active', true)
            ->where('session_id', '!=', $sessionId)
            ->count();
        
        // If no other active sessions, set kapal status to idle
        if ($otherActiveSessions == 0) {
            $kapal->update(['status' => 'idle']);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Monitoring session berhasil dihentikan',
        ]);
    }

    public function getMonitoringSessionDetail($sessionId)
    {
        $email = session('user_email', 'dummy@example.com');
        
        $session = MonitoringSession::find($sessionId);
        
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Monitoring session tidak ditemukan',
            ], 404);
        }
        
        // Verify the session belongs to user's kapal
        $kapal = RobotKapalEshrimp::where('robot_id', $session->nama_kapal)
            ->where('email_peternak', $email)
            ->first();
        
        if (!$kapal) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk melihat session ini',
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'session' => $session,
        ]);
    }

    public function pauseMonitoringSession($sessionId)
    {
        $email = session('user_email', 'dummy@example.com');
        
        $session = MonitoringSession::find($sessionId);
        
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Monitoring session tidak ditemukan',
            ], 404);
        }
        
        // Verify the session belongs to user's kapal
        $kapal = RobotKapalEshrimp::where('robot_id', $session->nama_kapal)
            ->where('email_peternak', $email)
            ->first();
        
        if (!$kapal) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk pause session ini',
            ], 403);
        }
        
        if ($session->is_paused) {
            return response()->json([
                'success' => false,
                'message' => 'Session sudah dalam status pause',
            ], 400);
        }
        
        $session->update([
            'is_paused' => true,
            'paused_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Monitoring session berhasil di-pause',
        ]);
    }

    public function resumeMonitoringSession($sessionId)
    {
        $email = session('user_email', 'dummy@example.com');
        
        $session = MonitoringSession::find($sessionId);
        
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Monitoring session tidak ditemukan',
            ], 404);
        }
        
        // Verify the session belongs to user's kapal
        $kapal = RobotKapalEshrimp::where('robot_id', $session->nama_kapal)
            ->where('email_peternak', $email)
            ->first();
        
        if (!$kapal) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk resume session ini',
            ], 403);
        }
        
        if (!$session->is_paused) {
            return response()->json([
                'success' => false,
                'message' => 'Session tidak dalam status pause',
            ], 400);
        }
        
        // Calculate paused duration
        $pausedAt = $session->paused_at ?? now();
        $pausedSeconds = now()->diffInSeconds($pausedAt);
        $totalPausedSeconds = ($session->total_paused_seconds ?? 0) + $pausedSeconds;
        
        $session->update([
            'is_paused' => false,
            'resumed_at' => now(),
            'total_paused_seconds' => $totalPausedSeconds,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Monitoring session berhasil di-resume',
        ]);
    }

    public function restartMonitoringSession($sessionId)
    {
        $email = session('user_email', 'dummy@example.com');
        
        $session = MonitoringSession::find($sessionId);
        
        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Monitoring session tidak ditemukan',
            ], 404);
        }
        
        // Verify the session belongs to user's kapal
        $kapal = RobotKapalEshrimp::where('robot_id', $session->nama_kapal)
            ->where('email_peternak', $email)
            ->first();
        
        if (!$kapal) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk restart session ini',
            ], 403);
        }
        
        $session->update([
            'is_active' => true,
            'is_paused' => false,
            'mulai_monitoring' => now(),
            'selesai_monitoring' => null,
            'paused_at' => null,
            'resumed_at' => null,
            'total_paused_seconds' => 0,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Monitoring session berhasil di-restart',
        ]);
    }
}

