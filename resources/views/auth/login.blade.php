@extends('layouts.stisla-login')

@section('title', 'Login - Laporan Sarana')

@section('content')
<style>
  .card-body {
    min-height: 400px;
    overflow-y: auto;
  }
</style>

<section class="section">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-8 col-md-6 col-lg-6 col-xl-4">
        <!-- Logo -->
        <div class="text-center mb-4">
          <img src="{{ asset('assets/img/stisla-fill.svg') }}" alt="Logo" width="100" class="shadow-light rounded-circle">
        </div>

        <div class="card card-primary">
          <div class="card-body p-4">
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
              {{ $errors->first('msg') ?? 'Terjadi kesalahan.' }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            @endif

            <!-- Judul Login -->
            <h4 class="text-center mb-4">Login</h4>

            <!-- Toggle Siswa/Admin -->
            <div class="d-flex justify-content-center mb-4">
              <div class="btn-group w-100" role="group">
                <button type="button" class="btn btn-primary btn-lg flex-fill" id="btn-siswa">
                  <i class="fas fa-graduation-cap mr-2"></i> Siswa
                </button>
                <button type="button" class="btn btn-outline-primary btn-lg flex-fill" id="btn-admin">
                  <i class="fas fa-cog mr-2"></i> Admin
                </button>
              </div>
            </div>

            <!-- Form Siswa -->
            <div id="form-siswa" class="form-container" style="display: block;">
              <form method="POST" action="{{ route('login.siswa') }}" class="needs-validation" novalidate="">
                @csrf
                <div class="form-group">
                  <label for="nama">Nama Lengkap</label>
                  <input id="nama" type="text" class="form-control" name="nama" required autofocus>
                  <div class="invalid-feedback">Masukkan nama lengkap Anda</div>
                </div>
                <div class="form-group">
                  <label for="kelas">Kelas</label>
                  <input id="kelas" type="text" class="form-control" name="kelas" required placeholder="Contoh: X RPL 1">
                  <div class="invalid-feedback">Masukkan kelas Anda</div>
                </div>
                <div class="form-group">
                  <label for="nis">NIS</label>
                  <input id="nis" type="text" class="form-control" name="nis" required>
                  <div class="invalid-feedback">Masukkan NIS Anda</div>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-lg btn-block">Masuk sebagai Siswa</button>
                </div>
              </form>
            </div>

            <!-- Form Admin -->
            <div id="form-admin" class="form-container" style="display: none;">
              <form method="POST" action="{{ route('login.admin') }}" class="needs-validation" novalidate="">
                @csrf
                <div class="form-group">
                  <label for="username">Username</label>
                  <input id="username" type="text" class="form-control" name="username" required autofocus>
                  <div class="invalid-feedback">Masukkan username</div>
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input id="password" type="password" class="form-control" name="password" required>
                  <div class="invalid-feedback">Masukkan password</div>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-lg btn-block">Masuk sebagai Admin</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4">
          <p class="text-muted">Copyright &copy; {{ date('Y') }} Laporan Sarana Sekolah</p>
        </div>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide success alert
    const alert = document.getElementById('autoHideAlert');
    if (alert) {
      setTimeout(() => {
        alert.classList.remove('show');
        alert.classList.add('fade');
        setTimeout(() => alert.remove(), 150); // hapus dari DOM setelah animasi
      }, 3000); // 3 detik
    }

    // Toggle form Siswa/Admin
    const btnSiswa = document.getElementById('btn-siswa');
    const btnAdmin = document.getElementById('btn-admin');
    const formSiswa = document.getElementById('form-siswa');
    const formAdmin = document.getElementById('form-admin');

    function switchToSiswa() {
      btnSiswa.classList.add('btn-primary');
      btnSiswa.classList.remove('btn-outline-primary');
      btnAdmin.classList.add('btn-outline-primary');
      btnAdmin.classList.remove('btn-primary');
      formSiswa.style.display = 'block';
      formAdmin.style.display = 'none';
    }

    function switchToAdmin() {
      btnAdmin.classList.add('btn-primary');
      btnAdmin.classList.remove('btn-outline-primary');
      btnSiswa.classList.add('btn-outline-primary');
      btnSiswa.classList.remove('btn-primary');
      formAdmin.style.display = 'block';
      formSiswa.style.display = 'none';
    }

    btnSiswa.addEventListener('click', switchToSiswa);
    btnAdmin.addEventListener('click', switchToAdmin);

    switchToSiswa();
  });
</script>
@endpush
@endsection