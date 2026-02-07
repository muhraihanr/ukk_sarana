<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelaporan;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }

        // Hitung statistik (tetap muncul di semua halaman)
        $totalMasuk = Pelaporan::where('status', 'masuk')->count();
        $totalDiproses = Pelaporan::where('status', 'diproses')->count();
        $totalSelesai = Pelaporan::where('status', 'selesai')->count();

        // Ambil laporan terbaru dengan pagination (5 per halaman)
        $laporans = Pelaporan::with('kategori')
                            ->orderBy('id_pelaporan', 'DESC')
                            ->paginate(5);

        return view('dashboard.admin', compact(
            'totalMasuk', 
            'totalDiproses', 
            'totalSelesai', 
            'laporans'
        ));
    }
}