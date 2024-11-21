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
    
@endpush

@section('content')
    <x-breadcrumb
    :values="[__('Barang Masuk'), __('Barang Masuk')]">
    <a href="{{ route('transaksi.barangmasuk.create') }}" class="btn btn-primary">
        {{ __('Tambah Permintaan') }}
    </a>
    </x-breadcrumb>


    <div class="card mb-5">
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                <tr>
                    <th>No</th> 
                    <th>{{ __('No Trans') }}</th> 
                    <th>{{ __('Suplier') }}</th> 
                    <th>{{ __('Tanggal') }}</th> 
                    <th>{{ __('Aksi') }}</th>
                  
                </tr>
                </thead>
                @if($BarangmasukList && $BarangmasukList->count())
                    <tbody>
                    @foreach($BarangmasukList  as $barangMasuk)
                        <tr>
                            <td>{{ ($BarangmasukList->currentPage() - 1) * $BarangmasukList->perPage() + $loop->iteration }}</td>
                            <td class="dropdown-trigger">
                                <span class="no-trans">{{ $barangMasuk->no_transaksi }}</span>
                                <div class="dropdown-content">
                                    <a href="{{ route('transaksi.barangmasuk.show', $barangMasuk->id_masuk) }}">
                                        <i class="fa fa-search"></i> Lihat Detail
                                    </a>
                                </div>
                            </td>
                            <td>{{ $barangMasuk->suplier->nama_suplier ?? 'N/A' }}</td>
                            <td>{{ $barangMasuk->created_at }}</td>
                            <td>{{ $barangMasuk->keterangan }}</td>

                       

                            
                            
                             <td>
                                <button class="btn btn-info btn-sm btn-edit"
                                        data-id="{{ $barangMasuk->id_masuk }}"
                                        data-no_transaksi="{{ $barangMasuk->no_transaksi }}"
                                        data-tgl_masuk="{{ $barangMasuk->tgl_masuk }}"
                                        data-keterangan="{{ $barangMasuk->keterangan }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal">
                                    {{ __('menu.general.edit') }}
                                </button>
                                <form action="" class="d-inline" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm btn-delete"
                                            type="button">{{ __('menu.general.delete') }}</button>
                                </form>
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
                
            </table>
        </div>
    </div>

     {!! $BarangmasukList->appends(['search' => $search])->links() !!} 

    
@endsection
