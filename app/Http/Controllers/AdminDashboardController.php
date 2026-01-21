<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelaporan;

class AdminDashboardController extends Controller
{
    public function index()
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }

        // Jika pakai kolom status
        $totalMasuk = Pelaporan::where('status', 'masuk')->count();
        $totalDiproses = Pelaporan::where('status', 'diproses')->count();
        $totalSelesai = Pelaporan::where('status', 'selesai')->count();

        // Jika belum pakai status, gunakan ini:
        // $totalMasuk = Pelaporan::count();
        // $totalSelesai = 0;

        $laporans = Pelaporan::with('kategori')
                            ->orderBy('id_pelaporan', 'DESC')
                            ->limit(5)
                            ->get();

        return view('dashboard.admin', compact('totalMasuk', 'totalSelesai', 'laporans'));
    }
}