@if($siswas->isEmpty())
<div class="text-center py-4 text-muted">Tidak ada data siswa.</div>
@else
<div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswas as $siswa)
            <tr>
                <td>{{ $loop->iteration + ($siswas->currentPage() - 1) * $siswas->perPage() }}</td>
                <td>{{ $siswa->nama }}</td>
                <td>{{ $siswa->nis }}</td>
                <td>{{ $siswa->kelas }}</td>
                <td>
                    <a href="{{ route('admin.siswa.edit', $siswa) }}" class="btn btn-sm btn-warning" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" 
                            class="btn btn-sm btn-danger delete-btn" 
                            data-id="{{ $siswa->id }}"
                            title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination Standar Laravel -->
<div class="card-footer d-flex justify-content-end">
    {{ $siswas->appends(request()->query())->render('vendor.pagination.stisla') }}
</div>
@endif