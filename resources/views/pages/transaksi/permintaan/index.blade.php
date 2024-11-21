@extends('layout.main')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
.no-trans {
    font-weight: bolder;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #E0E0E0;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-content a {
    color: #404040;
    padding: 12px 16px;
    text-decoration: none;
    display: flex;
    align-items: center;
}

.dropdown-content a i {
    margin-right: 8px;
}

.dropdown-trigger:hover .dropdown-content {
    display: block;
}

.dropdown-trigger {
    position: relative;
    cursor: pointer;
}


</style>
@push('script')
    <script>
    $(document).on('click', '.btn-edit', function () {
    const id = $(this).data('id'); // id_permintaan
    const keterangan = $(this).data('keterangan'); // keterangan dari tabel permintaan
    const tgl_permintaan = $(this).data('tgl_permintaan'); // tgl_permintaan dari tabel permintaan
    const bagian = $(this).data('bagian'); // bagian dari tabel permintaan
    const tipe_id = $(this).data('tipe_id'); // tipe_id dari tabel permintaan

    // Set form action dengan ID permintaan
    $('#editModal form').attr('action', '{{ route("transaksi.permintaan.update", ":id") }}'.replace(':id', id));

    // Isi field-field di modal
    $('#edit-keterangan').val(keterangan);
    $('#edit-tgl_permintaan').val(tgl_permintaan);

    // Set selected option untuk bagian
    $('#edit-bagian').val(bagian);

    // Set selected option untuk tipe
    $('#edit-tipe').val(tipe_id);
});
    </script>
@endpush

@section('content')
    <x-breadcrumb
    :values="[__('Permintaan'), __('Permintaan')]">
    <a href="{{ route('transaksi.permintaan.create') }}" class="btn btn-primary">
        {{ __('Tambah Permintaan') }}
    </a>
            {{-- <a href="{{ route('create.permission.add') }}" class="btn btn-primary">{{ __('') }}</a>  --}}
    </x-breadcrumb>


    <div class="card mb-5">
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th>No</th> <!-- Kolom Nomor -->
                    <th>{{ __('No Trans') }}</th> <!-- Kolom Kode Barang -->
                    <th>{{ __('Nama') }}</th> <!-- Kolom Deskripsi -->
                    <th>{{ __('Bagian Meminta') }}</th> <!-- Kolom Kode Jenis -->
                    <th>{{ __('Jenis') }}</th>
                    <th>{{ __('Tanggal') }}</th> <!-- Kolom Nama Jenis -->
                    <th>{{ __('Keterangan') }}</th> <!-- Kolom Unit -->
                    <th>{{ __('Aksi') }}</th>
                  
                </tr>
                </thead>
                @if($data && $data->count())
                    <tbody>
                    @foreach($data as $permintaan)
                        <tr>
                            <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                            <td class="dropdown-trigger">
                                <span class="no-trans">{{ $permintaan->no_trans }}</span>
                                <div class="dropdown-content">
                                    <a href="{{ route('transaksi.permintaan.show', $permintaan->id_permintaan) }}">
                                        <i class="fa fa-search"></i> Lihat Detail
                                    </a>
                                </div>
                            </td>
                            
                            <td>{{ $permintaan->user->name ?? '-' }}</td> <!-- Deskripsi -->
                            {{-- <td>{{ $permintaan->bagian ? $permintaan->bagian->bagian : '-' }}</td> <!-- Pastikan ini benar --> --}}
                            <td>{{ $permintaan->bagiann->nama_bagian ?? '-' }}</td> <!-- Kode Barang -->
                            <td>{{ $permintaan->tipe->nama_tipe ?? '-' }}</td>
                            <td>{{ $permintaan->tgl_permintaan }}</td>
                            <td>{{ $permintaan->keterangan }}</td>

                            
                            {{-- <td><span
                                    class="badge bg-label-primary me-1">{{  __('model.user.' . ($user->is_active ? 'active' : 'nonactive')) }}</span>
                            </td> --}}
                            <td>
                                <button class="btn btn-info btn-sm btn-edit"
                                        data-id="{{ $permintaan->id_permintaan }}"
                                        data-keterangan="{{ $permintaan->keterangan }}"
                                        data-tgl_permintaan="{{ $permintaan->tgl_permintaan }}"
                                        data-bagian="{{ $permintaan->bagian }}"
                                        data-tipe_id="{{ $permintaan->tipe_id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal">
                                    {{ __('menu.general.edit') }}
                                </button>
                                {{-- <form action="{{ route('transaksi.permintaan.destroy', $permintaan->id_permintaan) }}" 
                                      class="d-inline" 
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm btn-delete"
                                            type="submit">{{ __('menu.general.delete') }}</button>
                                </form> --}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                @else
                    <tbody>
                    <tr>
                        <td colspan="4" class="text-center">
                            {{ __('menu.general.empty') }}
                        </td>
                    </tr>
                    </tbody>
                @endif
                {{-- <tfoot class="table-border-bottom-0">
                <tr>
                    <th>{{ __('model.user.name') }}</th>
                    <th>{{ __('model.user.email') }}</th>
                    <th>{{ __('model.user.phone') }}</th>
                    <th>{{ __('model.user.is_active') }}</th>
                    <th>{{ __('menu.general.action') }}</th>
                </tr>
                </tfoot> --}}
            </table>
        </div>
    </div>

     {!! $data->appends(['search' => $search])->links() !!} 
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('transaksi.permintaan.update', $permintaan->id_permintaan) }}">
                @csrf
                @method('PUT')
                
                <div class="modal-header">
                    <h5 class="modal-title">Edit Permintaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Bagian</label>
                        <select class="form-control" name="bagian" id="edit-bagian">
                            @foreach($bagians as $bagian)
                                <option value="{{ $bagian->id_bagian }}">{{ $bagian->nama_bagian }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tipe</label>
                        <select class="form-control" name="tipe_id" id="edit-tipe">
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id_tipe }}">{{ $kategori->nama_tipe }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" class="form-control" name="keterangan" id="edit-keterangan">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal Permintaan</label>
                        <input type="date" class="form-control" name="tgl_permintaan" id="edit-tgl_permintaan">
                    </div>
                    
                    
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
@endsection
