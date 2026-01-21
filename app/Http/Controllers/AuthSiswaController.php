<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Session;

class AuthSiswaController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login-siswa');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'kelas' => 'required|string',
            'nis' => 'required|string',
        ]);

        // Cari siswa berdasarkan ketiga field
        $siswa = Siswa::where('nama', $request->nama)
            ->where('kelas', $request->kelas)
            ->where('nis', $request->nis)
            ->first();

        if ($siswa) {
            // Simpan data siswa ke session
            Session::put('siswa_id', $siswa->id);
            Session::put('siswa_nama', $siswa->nama);
            Session::put('siswa_kelas', $siswa->kelas);
            Session::put('siswa_nis', $siswa->nis);

            return redirect()->route('dashboard.siswa')->with('success', 'Login berhasil!');
        }

        return back()->withErrors(['msg' => 'Data tidak ditemukan. Pastikan nama, kelas, dan NIS benar.']);
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('login.siswa')->with('success', 'Anda telah keluar.');
    }
}
