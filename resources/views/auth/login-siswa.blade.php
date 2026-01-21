@extends('layouts.stisla-login')

@section('title', 'Login Siswa - Laporan Sarana')

@section('content')
<section class="section">
  <div class="container mt-5">
    <div class="row">
      <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
        <div class="login-brand">
          <img src="{{ asset('assets/img/stisla-fill.svg') }}" alt="Logo Sekolah" width="100" class="shadow-light rounded-circle">
        </div>

        <div class="card card-primary">
          <div class="card-header"><h4>Login Siswa</h4></div>

          <div class="card-body">
            @if(session('success'))
              <div id="autoHideAlert" class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif

            @if($errors->any())
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first('msg') ?? 'Data tidak valid.' }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif

            <form method="POST" action="{{ route('login.siswa.process') }}" class="needs-validation" novalidate="">
              @csrf

              <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input id="nama" type="text" class="form-control" name="nama" tabindex="1" required autofocus>
                <div class="invalid-feedback">
                  Masukkan nama lengkap Anda
                </div>
              </div>

              <div class="form-group">
                <label for="kelas">Kelas</label>
                <input id="kelas" type="text" class="form-control" name="kelas" tabindex="2" required placeholder="Contoh: X RPL 1">
                <div class="invalid-feedback">
                  Masukkan kelas Anda
                </div>
              </div>

              <div class="form-group">
                <label for="nis">NIS</label>
                <input id="nis" type="text" class="form-control" name="nis" tabindex="3" required>
                <div class="invalid-feedback">
                  Masukkan NIS Anda
                </div>
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                  Masuk
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="simple-footer">
          Copyright &copy; {{ date('Y') }} Laporan Sarana Sekolah
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ✅ Script Auto-Hide Alert --}}
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const alert = document.getElementById('autoHideAlert');
    if (alert) {
      // Hilangkan alert setelah 3 detik (3000 ms)
      setTimeout(() => {
        alert.classList.remove('show');
        alert.classList.add('fade');
        setTimeout(() => alert.remove(), 150); // Hapus dari DOM setelah animasi
      }, 3000);
    }
  });
</script>
@endpush
@endsection