<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\Siswa;
use App\Models\Admin;

class AuthController extends Controller
{
    public function showLoginForm($type = 'siswa')
    {
        return view('auth.login');
    }

    // Login Siswa
    public function loginSiswa(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'kelas' => 'required|string',
            'nis' => 'required|string',
        ]);

        $siswa = Siswa::where('nama', $request->nama)
            ->where('kelas', $request->kelas)
            ->where('nis', $request->nis)
            ->first();

        if ($siswa) {
            // Hapus session admin jika ada
            Session::forget(['admin_id', 'admin_username']);

            // Simpan session siswa
            Session::put('user_role', 'siswa');
            Session::put('siswa_id', $siswa->id);
            Session::put('siswa_nama', $siswa->nama);
            Session::put('siswa_kelas', $siswa->kelas);
            Session::put('siswa_nis', $siswa->nis);

            return redirect()->route('dashboard.siswa')->with('success', 'Login berhasil!');
        }

        return back()->withErrors(['msg' => 'Data siswa tidak ditemukan.']);
    }

    // Login Admin
    public function loginAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            // Hapus session siswa jika ada
            Session::forget(['siswa_id', 'siswa_nama', 'siswa_kelas', 'siswa_nis']);

            // Simpan session admin
            Session::put('user_role', 'admin');
            Session::put('admin_id', $admin->id);
            Session::put('admin_username', $admin->username);

            return redirect()->route('dashboard.admin')->with('success', 'Login admin berhasil!');
        }

        return back()->withErrors(['msg' => 'Username atau password salah.']);
    }


    public function logout()
    {
        $userRole = Session::get('user_role');

        // Hapus semua session
        Session::flush();

        // Redirect sesuai role sebelum logout
        if ($userRole === 'admin') {
            return redirect()->route('login.form', 'admin')->with('success', 'Anda telah keluar.');
        } else {
            return redirect()->route('login.form', 'siswa')->with('success', 'Anda telah keluar.');
        }
    }
}
