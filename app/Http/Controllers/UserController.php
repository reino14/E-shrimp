<?php

namespace App\Http\Controllers;

use App\Models\DashboardMonitoring;
use App\Models\SensorData;
use App\Models\Threshold;
use App\Models\Notifikasi;
use App\Models\Artikel;
use App\Models\Forum;
use App\Models\Peternak;
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

    public function historiData()
    {
        $historicalData = SensorData::latest('waktu')->paginate(50);
        return view('user.histori-data', compact('historicalData'));
    }

    public function grafikRealtime()
    {
        $historicalData = SensorData::where('waktu', '>=', now()->subDay())
            ->orderBy('waktu')
            ->get();
        return view('user.grafik-realtime', compact('historicalData'));
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
        // Dummy prediction data
        $prediksi = [
            'berat_estimasi' => 25.5,
            'panjang_estimasi' => 12.3,
            'kebutuhan_pakan' => 150,
            'tanggal_panen_estimasi' => now()->addDays(30),
        ];
        
        return view('user.prediksi-pertumbuhan', compact('prediksi'));
    }

    public function profil()
    {
        $email = session('user_email');
        $peternak = null;
        
        if ($email && $email !== 'dummy@example.com') {
            $peternak = Peternak::where('email_peternak', $email)->first();
        }
        
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

    public function klasifikasiKualitas()
    {
        $email = session('user_email');
        $kolam = DashboardMonitoring::where('email_peternak', $email)->first();
        
        // Get latest sensor data
        $latestData = SensorData::latest('waktu')->first();
        
        // Calculate quality classification
        $klasifikasi = [
            'ph' => $this->klasifikasiPH($latestData->ph ?? 7.0),
            'suhu' => $this->klasifikasiSuhu($latestData->suhu ?? 28.0),
            'oksigen' => $this->klasifikasiOksigen($latestData->oksigen ?? 7.0),
            'salinitas' => $this->klasifikasiSalinitas($latestData->salinitas ?? 15.0),
            'overall' => $this->klasifikasiOverall($latestData),
        ];
        
        return view('user.klasifikasi-kualitas', compact('latestData', 'klasifikasi'));
    }

    private function klasifikasiPH($ph)
    {
        if ($ph >= 7.0 && $ph <= 8.5) {
            return ['status' => 'Optimal', 'kategori' => 'Baik', 'warna' => 'green', 'deskripsi' => 'pH dalam rentang optimal untuk budidaya udang vaname'];
        } elseif ($ph >= 6.5 && $ph < 7.0) {
            return ['status' => 'Asam Ringan', 'kategori' => 'Perhatian', 'warna' => 'yellow', 'deskripsi' => 'pH sedikit asam, perlu monitoring'];
        } elseif ($ph < 6.5) {
            return ['status' => 'Asam', 'kategori' => 'Buruk', 'warna' => 'red', 'deskripsi' => 'pH terlalu asam, perlu perbaikan segera'];
        } elseif ($ph > 8.5 && $ph <= 9.0) {
            return ['status' => 'Basa Ringan', 'kategori' => 'Perhatian', 'warna' => 'yellow', 'deskripsi' => 'pH sedikit basa, perlu monitoring'];
        } else {
            return ['status' => 'Basa', 'kategori' => 'Buruk', 'warna' => 'red', 'deskripsi' => 'pH terlalu basa, perlu perbaikan segera'];
        }
    }

    private function klasifikasiSuhu($suhu)
    {
        if ($suhu >= 26 && $suhu <= 32) {
            return ['status' => 'Optimal', 'kategori' => 'Baik', 'warna' => 'green', 'deskripsi' => 'Suhu dalam rentang optimal untuk pertumbuhan udang'];
        } elseif ($suhu >= 24 && $suhu < 26) {
            return ['status' => 'Dingin Ringan', 'kategori' => 'Perhatian', 'warna' => 'yellow', 'deskripsi' => 'Suhu sedikit dingin, pertumbuhan melambat'];
        } elseif ($suhu < 24) {
            return ['status' => 'Dingin', 'kategori' => 'Buruk', 'warna' => 'red', 'deskripsi' => 'Suhu terlalu dingin, berisiko stress'];
        } elseif ($suhu > 32 && $suhu <= 34) {
            return ['status' => 'Panas Ringan', 'kategori' => 'Perhatian', 'warna' => 'yellow', 'deskripsi' => 'Suhu sedikit panas, perlu pendinginan'];
        } else {
            return ['status' => 'Panas', 'kategori' => 'Buruk', 'warna' => 'red', 'deskripsi' => 'Suhu terlalu panas, berisiko kematian'];
        }
    }

    private function klasifikasiOksigen($oksigen)
    {
        if ($oksigen >= 5 && $oksigen <= 10) {
            return ['status' => 'Optimal', 'kategori' => 'Baik', 'warna' => 'green', 'deskripsi' => 'Kadar oksigen terlarut dalam rentang optimal'];
        } elseif ($oksigen >= 4 && $oksigen < 5) {
            return ['status' => 'Rendah Ringan', 'kategori' => 'Perhatian', 'warna' => 'yellow', 'deskripsi' => 'Oksigen sedikit rendah, perlu aerasi'];
        } elseif ($oksigen < 4) {
            return ['status' => 'Rendah', 'kategori' => 'Buruk', 'warna' => 'red', 'deskripsi' => 'Oksigen terlalu rendah, berisiko kematian'];
        } elseif ($oksigen > 10 && $oksigen <= 12) {
            return ['status' => 'Tinggi Ringan', 'kategori' => 'Perhatian', 'warna' => 'yellow', 'deskripsi' => 'Oksigen sedikit tinggi, normal'];
        } else {
            return ['status' => 'Tinggi', 'kategori' => 'Baik', 'warna' => 'green', 'deskripsi' => 'Oksigen tinggi, kondisi sangat baik'];
        }
    }

    private function klasifikasiSalinitas($salinitas)
    {
        if ($salinitas >= 10 && $salinitas <= 20) {
            return ['status' => 'Optimal', 'kategori' => 'Baik', 'warna' => 'green', 'deskripsi' => 'Salinitas dalam rentang optimal untuk udang vaname'];
        } elseif ($salinitas >= 8 && $salinitas < 10) {
            return ['status' => 'Rendah Ringan', 'kategori' => 'Perhatian', 'warna' => 'yellow', 'deskripsi' => 'Salinitas sedikit rendah'];
        } elseif ($salinitas < 8) {
            return ['status' => 'Rendah', 'kategori' => 'Buruk', 'warna' => 'red', 'deskripsi' => 'Salinitas terlalu rendah, perlu penambahan garam'];
        } elseif ($salinitas > 20 && $salinitas <= 25) {
            return ['status' => 'Tinggi Ringan', 'kategori' => 'Perhatian', 'warna' => 'yellow', 'deskripsi' => 'Salinitas sedikit tinggi'];
        } else {
            return ['status' => 'Tinggi', 'kategori' => 'Buruk', 'warna' => 'red', 'deskripsi' => 'Salinitas terlalu tinggi, perlu pengenceran'];
        }
    }

    private function klasifikasiOverall($data)
    {
        if (!$data) {
            return ['status' => 'Tidak Ada Data', 'kategori' => 'Unknown', 'warna' => 'gray', 'deskripsi' => 'Belum ada data sensor'];
        }
        
        $ph = $this->klasifikasiPH($data->ph ?? 7.0);
        $suhu = $this->klasifikasiSuhu($data->suhu ?? 28.0);
        $oksigen = $this->klasifikasiOksigen($data->oksigen ?? 7.0);
        $salinitas = $this->klasifikasiSalinitas($data->salinitas ?? 15.0);
        
        $baik = 0;
        $perhatian = 0;
        $buruk = 0;
        
        foreach ([$ph, $suhu, $oksigen, $salinitas] as $k) {
            if ($k['kategori'] === 'Baik') $baik++;
            elseif ($k['kategori'] === 'Perhatian') $perhatian++;
            else $buruk++;
        }
        
        if ($buruk > 0) {
            return ['status' => 'Buruk', 'kategori' => 'Buruk', 'warna' => 'red', 'deskripsi' => 'Beberapa parameter dalam kondisi buruk, perlu perbaikan segera'];
        } elseif ($perhatian > 0) {
            return ['status' => 'Perhatian', 'kategori' => 'Perhatian', 'warna' => 'yellow', 'deskripsi' => 'Beberapa parameter perlu monitoring'];
        } else {
            return ['status' => 'Baik', 'kategori' => 'Baik', 'warna' => 'green', 'deskripsi' => 'Semua parameter dalam kondisi optimal'];
        }
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
}

