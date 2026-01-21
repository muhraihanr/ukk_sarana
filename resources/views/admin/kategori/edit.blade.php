@extends('layouts.stisla-dashboard')

@section('title', 'Edit Kategori - Admin')

@section('header', 'Edit Data Kategori')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.kategori.update', $kategori) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="ket_kategori">Nama Kategori</label>
                        <input type="text" class="form-control" id="ket_kategori" name="ket_kategori" value="{{ old('ket_kategori', $kategori->ket_kategori) }}" required placeholder="Contoh: Fasilitas Umum">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                        <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection