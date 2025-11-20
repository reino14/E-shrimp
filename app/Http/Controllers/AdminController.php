<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Peternak;
use App\Models\Artikel;
use App\Models\Forum;
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
}

