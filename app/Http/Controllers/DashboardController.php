<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelaporan;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::has('siswa_nis')) {
            return redirect()->route('login.siswa');
        }

        $query = Pelaporan::with('kategori')->orderBy('id_pelaporan', 'DESC');

        // Jika ada parameter pencarian
        if ($request->filled('cari')) {
            $search = $request->cari;
            $query->where(function ($q) use ($search) {
                $q->where('lokasi', 'LIKE', "%{$search}%")
                    ->orWhere('ket', 'LIKE', "%{$search}%")
                    ->orWhere('nama', 'LIKE', "%{$search}%");
            });
        }

        $laporans = $query->get();

        return view('dashboard.siswa', compact('laporans'));
    }

    public function riwayat()
    {
        if (!Session::has('siswa_nis')) {
            return redirect()->route('login.siswa');
        }

        $laporans = Pelaporan::with('kategori')
            ->where('nis', Session::get('siswa_nis'))
            ->orderBy('id_pelaporan', 'DESC')
            ->get();

        return view('dashboard.riwayat', compact('laporans'));
    }
}
