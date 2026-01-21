<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelaporan;
use App\Models\Kategori;

class AdminLaporanController extends Controller
{
    public function index(Request $request)
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }

        // Ambil nilai filter status (default: semua)
        $status = $request->get('status');

        // Query dasar
        $query = Pelaporan::with('kategori')->orderBy('id_pelaporan', 'DESC');

        // Terapkan filter jika ada
        if ($status && in_array($status, ['masuk', 'diproses', 'selesai'])) {
            $query->where('status', $status);
        }

        // Paginasi 5 per halaman
        $laporans = $query->paginate(5);

        return view('admin.laporan.index', compact('laporans', 'status'));
    }

    public function masuk(Request $request)
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }

        $laporans = Pelaporan::with('kategori')
            ->where('status', 'masuk')
            ->orderBy('id_pelaporan', 'DESC')
            ->paginate(5);

        return view('admin.laporan.masuk', compact('laporans'));
    }

    public function diproses(Request $request)
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }

        $laporans = Pelaporan::with('kategori')
            ->where('status', 'diproses')
            ->orderBy('id_pelaporan', 'DESC')
            ->paginate(5);

        return view('admin.laporan.diproses', compact('laporans'));
    }

    public function selesai(Request $request)
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }

        $laporans = Pelaporan::with('kategori')
            ->where('status', 'selesai')
            ->orderBy('id_pelaporan', 'DESC')
            ->paginate(5);

        return view('admin.laporan.selesai', compact('laporans'));
    }

    // Update status laporan
    public function updateStatus($id, $status)
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }

        $validStatus = ['masuk', 'diproses', 'selesai'];
        if (!in_array($status, $validStatus)) {
            abort(400, 'Status tidak valid.');
        }

        $laporan = Pelaporan::findOrFail($id);
        $laporan->status = $status;
        $laporan->save();

        return back()->with('success', 'Status laporan berhasil diperbarui!');
    }

    // Hapus laporan
    public function destroy($id)
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }

        $laporan = Pelaporan::findOrFail($id);

        // Hapus lampiran jika ada
        if ($laporan->lampiran && file_exists(public_path('uploads/laporan/' . $laporan->lampiran))) {
            unlink(public_path('uploads/laporan/' . $laporan->lampiran));
        }

        $laporan->delete();

        return redirect()->route('admin.laporan.index')
            ->with('success', 'Laporan berhasil dihapus!');
    }
}
