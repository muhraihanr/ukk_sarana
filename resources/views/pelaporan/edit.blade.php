@extends('layouts.stisla-dashboard')

@section('title', 'Edit Laporan - ' . $laporan->lokasi)

@section('header', 'Edit Laporan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit"></i> Edit Laporan</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('pelaporan.update', $laporan->id_pelaporan) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Kategori Laporan</label>
                        <select name="id_kategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id_kategori }}" {{ $laporan->id_kategori == $kategori->id_kategori ? 'selected' : '' }}>
                                    {{ $kategori->ket_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Lokasi Sarana</label>
                        <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $laporan->lokasi) }}" placeholder="Contoh: Ruang Kelas X RPL 1" required>
                    </div>

                    <div class="form-group">
                        <label>Keterangan / Deskripsi Masalah</label>
                        <textarea name="ket" class="form-control" rows="5" required>{{ old('ket', $laporan->ket) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Lampiran Foto (Opsional)</label>
                        @if($laporan->lampiran)
                            <div class="mb-2">
                                <img src="{{ asset('uploads/laporan/' . $laporan->lampiran) }}" 
                                     alt="Lampiran" 
                                     class="img-fluid border rounded" 
                                     style="max-height: 150px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="lampiran" class="form-control-file">
                        <small class="text-muted">Format: JPG, PNG, GIF (max 2MB)</small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Perbarui Laporan</button>
                        <a href="{{ route('riwayat.siswa') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection