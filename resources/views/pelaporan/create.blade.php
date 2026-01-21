@extends('layouts.stisla-dashboard')

@section('title', 'Laporkan Sarana')

@section('header', 'Form Laporan Sarana')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>Isi Laporan</h4>
      </div>
      <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ $errors->first('msg') ?? 'Mohon periksa kembali data Anda.' }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @endif

        <form method="POST" action="{{ route('pelaporan.store') }}" enctype="multipart/form-data">
          @csrf

          <input type="hidden" name="nama" value="{{ session('siswa_nama') }}">
          <input type="hidden" name="kelas" value="{{ session('siswa_kelas') }}">
          <input type="hidden" name="nis" value="{{ session('siswa_nis') }}">

          <div class="form-group">
            <label>Kategori Laporan</label>
            <select name="id_kategori" class="form-control" required>
              <option value="">-- Pilih Kategori --</option>
              @foreach($kategoris as $kategori)
              <option value="{{ $kategori->id_kategori }}">{{ $kategori->ket_kategori }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Lokasi Sarana</label>
            <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Ruang Kelas X RPL 1, Toilet Lantai 2, dll" required>
          </div>

          <div class="form-group">
            <label>Keterangan / Deskripsi Masalah</label>
            <textarea name="ket" class="form-control" rows="5" placeholder="Jelaskan kondisi sarana yang ingin dilaporkan..." required></textarea>
          </div>

          <div class="form-group">
            <label>Lampiran Foto (Opsional)</label>
            <input type="file" name="lampiran" class="form-control-file">
            <small class="text-muted">Format: JPG, PNG, GIF (max 2MB)</small>
          </div>

          <div class="form-group">
            <button type="submit" class="btn btn-primary">Kirim Laporan</button>
            <a href="{{ route('dashboard.siswa') }}" class="btn btn-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection