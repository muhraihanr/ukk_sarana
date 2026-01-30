@if($kategoris->isEmpty())
<div class="text-center py-4 text-muted">Tidak ada data kategori.</div>
@else
<div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kategoris as $kategori)
            <tr>
                <td>{{ $loop->iteration + ($kategoris->currentPage() - 1) * $kategoris->perPage() }}</td>
                <td>{{ $kategori->ket_kategori }}</td>
                <td>
                    <a href="{{ route('admin.kategori.edit', $kategori) }}" class="btn btn-sm btn-warning" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button"
                        class="btn btn-sm btn-danger delete-btn"
                        data-id="{{ $kategori->id_kategori }}"
                        title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="card-footer d-flex justify-content-end">
    {{ $kategoris->appends(request()->query())->render('vendor.pagination.stisla') }}
</div>
@endif