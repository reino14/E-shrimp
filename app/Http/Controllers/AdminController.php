<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Peternak;
use App\Models\Artikel;
use App\Models\Forum;
use App\Models\DashboardMonitoring;
use App\Models\MonitoringSession;
use App\Models\SensorData;
use App\Models\Notifikasi;
use App\Models\Threshold;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = Peternak::count();
        $totalArtikel = Artikel::count();
        $totalForum = Forum::count();
        
        return view('admin.dashboard', compact('totalUsers', 'totalArtikel', 'totalForum'));
    }

    public function kelolaUser()
    {
        // Data peternak sekarang diambil dari Firebase RDTB via JavaScript
        // Laravel model hanya untuk backup/sync jika diperlukan
        return view('admin.kelola-user');
    }

    public function hapusUser($email)
    {
        // Hapus dari Laravel database (backup)
        Peternak::where('email_peternak', $email)->delete();
        // Note: Hapus dari RDTB dilakukan via JavaScript di frontend
        return redirect()->route('admin.kelola-user')->with('success', 'User berhasil dihapus');
    }

    public function kelolaArtikel()
    {
        $artikels = Artikel::latest()->paginate(10);
        return view('admin.kelola-artikel', compact('artikels'));
    }

    public function tambahArtikel(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'konten' => 'required',
        ]);

        Artikel::create([
            'artikel_id' => 'ART' . time(),
            'judul' => $request->judul,
            'konten' => $request->konten,
            'tanggal' => now(),
        ]);

        return redirect()->route('admin.kelola-artikel')->with('success', 'Artikel berhasil ditambahkan');
    }

    public function editArtikel(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'konten' => 'required',
        ]);

        Artikel::where('artikel_id', $id)->update([
            'judul' => $request->judul,
            'konten' => $request->konten,
        ]);

        return redirect()->route('admin.kelola-artikel')->with('success', 'Artikel berhasil diupdate');
    }

    public function hapusArtikel($id)
    {
        Artikel::where('artikel_id', $id)->delete();
        return redirect()->route('admin.kelola-artikel')->with('success', 'Artikel berhasil dihapus');
    }

    public function kelolaForum()
    {
        // Data forum sekarang diambil dari Firebase RDTB via JavaScript
        // Laravel model hanya untuk backup/sync jika diperlukan
        return view('admin.kelola-forum');
    }

    public function hapusPostForum($id)
    {
        // Hapus dari Laravel database (backup)
        Forum::where('forum_id', $id)->delete();
        // Note: Hapus dari RDTB dilakukan via JavaScript di frontend
        return redirect()->route('admin.kelola-forum')->with('success', 'Post forum berhasil dihapus');
    }

    public function detailPeternak($email)
    {
        // Get peternak data
        $peternak = Peternak::where('email_peternak', $email)->first();
        
        if (!$peternak) {
            return redirect()->route('admin.kelola-user')->with('error', 'Peternak tidak ditemukan');
        }

        // Get all kolams owned by this peternak
        $kolams = DashboardMonitoring::where('email_peternak', $email)->get();

        // Get all monitoring sessions
        $kolamIds = $kolams->pluck('kolam_id')->toArray();
        $monitoringSessions = MonitoringSession::whereIn('kolam_id', $kolamIds)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get sensor data statistics
        $sensorDataStats = SensorData::whereIn('kolam_id', $kolamIds)
            ->selectRaw('
                COUNT(*) as total_records,
                AVG(ph) as avg_ph,
                AVG(suhu) as avg_suhu,
                AVG(oksigen) as avg_oksigen,
                AVG(salinitas) as avg_salinitas,
                MIN(waktu) as first_record,
                MAX(waktu) as last_record
            ')
            ->first();

        // Get notifications count
        $notificationsCount = Notifikasi::whereIn('kolam_id', $kolamIds)->count();
        $unreadNotificationsCount = Notifikasi::whereIn('kolam_id', $kolamIds)
            ->where('status', false)
            ->count();

        // Get thresholds count
        $thresholdsCount = Threshold::whereIn('kolam_id', $kolamIds)->count();

        // Get latest sensor data (last 10 records)
        $latestSensorData = SensorData::whereIn('kolam_id', $kolamIds)
            ->latest('waktu')
            ->take(10)
            ->get();

        // Get sensor count per kolam for display
        $kolamSensorCounts = [];
        foreach ($kolams as $kolam) {
            $kolamSensorCounts[$kolam->kolam_id] = SensorData::where('kolam_id', $kolam->kolam_id)->count();
        }

        // Get active sessions count per kolam
        $kolamActiveSessions = [];
        foreach ($kolams as $kolam) {
            $kolamActiveSessions[$kolam->kolam_id] = MonitoringSession::where('kolam_id', $kolam->kolam_id)
                ->where('is_active', true)
                ->count();
        }

        return view('admin.detail-peternak', compact(
            'peternak',
            'kolams',
            'monitoringSessions',
            'sensorDataStats',
            'notificationsCount',
            'unreadNotificationsCount',
            'thresholdsCount',
            'latestSensorData',
            'kolamSensorCounts',
            'kolamActiveSessions'
        ));
    }

    public function hapusKolam($kolamId)
    {
        try {
            $kolam = DashboardMonitoring::where('kolam_id', $kolamId)->first();
            
            if (!$kolam) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kolam tidak ditemukan',
                ], 404);
            }

            // Get email for redirect
            $email = $kolam->email_peternak;

            // Delete related data (cascade)
            MonitoringSession::where('kolam_id', $kolamId)->delete();
            SensorData::where('kolam_id', $kolamId)->delete();
            Notifikasi::where('kolam_id', $kolamId)->delete();
            Threshold::where('kolam_id', $kolamId)->delete();

            // Delete kolam
            $kolam->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kolam berhasil dihapus',
                'redirect' => route('admin.detail-peternak', $email),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function hapusMonitoringSession($sessionId)
    {
        try {
            $session = MonitoringSession::find($sessionId);
            
            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Monitoring session tidak ditemukan',
                ], 404);
            }

            // Get email for redirect
            $email = DashboardMonitoring::where('kolam_id', $session->kolam_id)
                ->value('email_peternak');

            // Delete session
            $session->delete();

            return response()->json([
                'success' => true,
                'message' => 'Monitoring session berhasil dihapus',
                'redirect' => route('admin.detail-peternak', $email),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}

