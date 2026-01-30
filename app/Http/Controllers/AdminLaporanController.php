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

        $query = Pelaporan::with('kategori')->orderBy('id_pelaporan', 'DESC');

        // Terapkan filter pencarian
        if ($request->filled('cari')) {
            $search = $request->cari;
            $query->where(function ($q) use ($search) {
                $q->where('lokasi', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%")
                    ->orWhere('kelas', 'LIKE', "%{$search}%")
                    ->orWhereHas('kategori', function ($kq) use ($search) {
                        $kq->where('ket_kategori', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Terapkan filter status
        if ($request->filled('status') && in_array($request->status, ['masuk', 'diproses', 'selesai'])) {
            $query->where('status', $request->status);
        }

        $laporans = $query->paginate(5);
        $status = $request->get('status');

        // Jika ini adalah request AJAX, return partial view
        if ($request->ajax()) {
            return view('admin.laporan.partials.laporan-table', compact('laporans', 'status'))->render();
        }

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
