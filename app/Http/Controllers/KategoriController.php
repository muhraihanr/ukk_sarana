<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }

        $query = Kategori::orderBy('id_kategori', 'DESC');

        if ($request->filled('cari')) {
            $search = $request->cari;
            $query->where('ket_kategori', 'LIKE', "%{$search}%");
        }

        $kategoris = $query->paginate(10);

        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ket_kategori' => 'required|string|max:255|unique:kategori,ket_kategori',
        ]);

        Kategori::create($request->only('ket_kategori'));

        return redirect()->route('admin.kategori.index')
                         ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Kategori $kategori)
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'ket_kategori' => 'required|string|max:255|unique:kategori,ket_kategori,' . $kategori->id_kategori . ',id_kategori',
        ]);

        $kategori->update($request->only('ket_kategori'));

        return redirect()->route('admin.kategori.index')
                         ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Kategori $kategori)
    {
        if (!session('admin_id')) {
            return redirect()->route('login.form', 'admin');
        }

        // Cek apakah kategori sedang digunakan
        if (\App\Models\Pelaporan::where('id_kategori', $kategori->id_kategori)->exists()) {
            return redirect()->route('admin.kategori.index')
                             ->with('error', 'Kategori tidak bisa dihapus karena sedang digunakan dalam laporan!');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
                         ->with('success', 'Kategori berhasil dihapus!');
    }
}