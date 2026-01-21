<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }

        $query = Siswa::orderBy('id', 'DESC');

        if ($request->filled('cari')) {
            $search = $request->cari;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nis', 'LIKE', "%{$search}%")
                  ->orWhere('kelas', 'LIKE', "%{$search}%");
            });
        }

        $siswas = $query->paginate(10);

        return view('admin.siswa.index', compact('siswas'));
    }

    public function create()
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:siswa,nis',
            'kelas' => 'required|string|max:100',
        ]);

        Siswa::create($request->only('nama', 'nis', 'kelas'));

        return redirect()->route('admin.siswa.index')
                         ->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function edit(Siswa $siswa)
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }
        return view('admin.siswa.edit', compact('siswa')); // ✅ Diperbaiki
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|unique:siswa,nis,' . $siswa->id,
            'kelas' => 'required|string|max:100',
        ]);

        $siswa->update($request->only('nama', 'nis', 'kelas'));

        return redirect()->route('admin.siswa.index') // ✅ Diperbaiki
                         ->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy(Siswa $siswa)
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }

        $siswa->delete();

        return redirect()->route('admin.siswa.index') // ✅ Diperbaiki
                         ->with('success', 'Data siswa berhasil dihapus!');
    }
}