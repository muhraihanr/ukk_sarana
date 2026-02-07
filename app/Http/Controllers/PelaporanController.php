<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Pelaporan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class PelaporanController extends Controller
{
    public function create()
    {
        if (!session('siswa_nis')) {
            return redirect()->route('login.form', 'siswa');
        }

        $kategoris = Kategori::all();
        return view('pelaporan.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        // Debug: cek apakah request masuk
        \Log::info('Store laporan dipanggil', $request->all());

        // Cek session siswa
        if (!session('siswa_nis')) {
            \Log::warning('Session siswa tidak ditemukan');
            return redirect()->route('login.form', 'siswa')->with('error', 'Sesi login telah berakhir. Silakan login ulang.');
        }

        // Validasi
        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'lokasi' => 'required|string|max:255',
            'ket' => 'required|string',
            'lampiran' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ], [
            'id_kategori.required' => 'Kategori wajib dipilih',
            'lokasi.required' => 'Lokasi wajib diisi',
            'ket.required' => 'Keterangan wajib diisi',
            'lampiran.image' => 'File harus berupa gambar',
            'lampiran.max' => 'Ukuran file maksimal 2MB',
        ]);

        try {
            // Handle upload file
            $lampiranPath = null;
            if ($request->hasFile('lampiran')) {
                $lampiranPath = $request->file('lampiran')->store('uploads/laporan', 'public');
            }

            // Simpan ke database
            $pelaporan = Pelaporan::create([
                'nama' => session('siswa_nama'),
                'kelas' => session('siswa_kelas'),
                'nis' => session('siswa_nis'),
                'id_kategori' => $validated['id_kategori'],
                'lokasi' => $validated['lokasi'],
                'ket' => $validated['ket'],
                'lampiran' => $lampiranPath,
                'status' => 'masuk',
            ]);

            \Log::info('Laporan berhasil disimpan', ['id' => $pelaporan->id_pelaporan]);

            return redirect()->route('dashboard.siswa')
                ->with('success', 'Laporan berhasil dikirim!');
        } catch (\Exception $e) {
            \Log::error('Error menyimpan laporan: ' . $e->getMessage());

            return back()
                ->withErrors(['msg' => 'Gagal menyimpan laporan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show($id_pelaporan)
    {
        // Cek apakah user login (admin atau siswa)
        if (!session('admin_id') && !session('siswa_nis')) {
            return redirect()->route('login.form', 'siswa');
        }

        $laporan = Pelaporan::with('kategori')
            ->where('id_pelaporan', $id_pelaporan)
            ->firstOrFail();

        // Untuk admin: boleh lihat semua laporan
        if (session('admin_id')) {
            return view('pelaporan.show', compact('laporan'));
        }

        // Untuk siswa: hanya boleh lihat laporan sendiri
        if (session('siswa_nis') && $laporan->nis == session('siswa_nis')) {
            return view('pelaporan.show', compact('laporan'));
        }

        // Jika tidak punya akses
        return redirect()->route('dashboard.siswa')
            ->withErrors(['msg' => 'Anda tidak memiliki akses ke laporan ini.']);
    }
    // Tampilkan form edit
    public function edit($id_pelaporan)
    {
        $laporan = Pelaporan::with('kategori')
            ->where('id_pelaporan', $id_pelaporan)
            ->firstOrFail();

        // 🔒 Hanya pemilik atau admin yang bisa edit
        if ($laporan->nis !== session('siswa_nis')) {
            abort(403, 'Anda tidak berhak mengedit laporan ini.');
        }

        $kategoris = Kategori::all();
        return view('pelaporan.edit', compact('laporan', 'kategoris'));
    }

    // Proses update
    public function update(Request $request, $id_pelaporan)
    {
        $laporan = Pelaporan::where('id_pelaporan', $id_pelaporan)
            ->where('nis', session('siswa_nis'))
            ->firstOrFail();

        $request->validate([
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'lokasi' => 'required|string|max:255',
            'ket' => 'required|string',
            'lampiran' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'id_kategori' => $request->id_kategori,
            'lokasi' => $request->lokasi,
            'ket' => $request->ket,
        ];

        // Handle upload file (konsisten dengan store)
        if ($request->hasFile('lampiran')) {
            // Hapus file lama jika ada
            if ($laporan->lampiran) {
                \Storage::disk('public')->delete($laporan->lampiran);
            }

            // Simpan file baru
            $lampiranPath = $request->file('lampiran')->store('uploads/laporan', 'public');
            $data['lampiran'] = $lampiranPath;
        }

        $laporan->update($data);

        return redirect()->route('dashboard.siswa')
            ->with('success', 'Laporan berhasil diperbarui!');
    }

    // Hapus laporan
    public function destroy($id_pelaporan)
    {
        $laporan = Pelaporan::where('id_pelaporan', $id_pelaporan)
            ->where('nis', session('siswa_nis'))
            ->firstOrFail();

        // Hapus file lampiran jika ada (konsisten)
        if ($laporan->lampiran) {
            \Storage::disk('public')->delete($laporan->lampiran);
        }

        $laporan->delete();

        return redirect()->route('dashboard.siswa')
            ->with('success', 'Laporan berhasil dihapus!');
    }
}
