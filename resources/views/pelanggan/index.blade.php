@extends('layouts.main', ['title' => 'Pelanggan'])
@section('title-content')
    <i class="fas fa-user mr-2"></i> Pelanggan
@endsection
@section('content')
    @if (session('store') == 'success')
        <x-alert type="success" >
            <strong>Berhasil dibuat!</strong>  Pelanggan berhasil dibuat.
        </x-alert>
    @endif
    @if (session('update') == 'success')
        <x-alert type="success" >
            <strong>Berhasil diupdate!</strong>  Pelanggan berhasil diupdate.
        </x-alert>
    @endif
    @if (session('destroy') == 'success')
        <x-alert type="success" >
            <strong>Berhasil dihapus!</strong>  Pelanggan berhasil dihapus.
        </x-alert>
    @endif
    @if (count($pelanggans) === 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Data Tidak ada.</strong>
        </div>
    @endif
    <div class="card card-orange card-outline">
        <div class="card-header form-inline">
            <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Pelanggan
            </a>
            <form action="" method="GET" class="ml-auto">
                <div class="input-group">
                    <input type="search" class="form-control" name="search" value="{{ request()->search }}" placeholder="Cari Pelanggan">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table table-stripped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Noomor</th>
                        <th>Alamat</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pelanggans as $key => $pelanggan)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $pelanggan->name }}</td>
                            <td>{{ $pelanggan->nomor_tlp }}</td>
                            <td>{{ $pelanggan->alamat }}</td>
                            <td class="text-right">
                                <a href="{{ route('pelanggan.edit', $pelanggan->id) }}" class="btn btn-xs text-success p-0 mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" data-url="{{ route('pelanggan.destroy', $pelanggan->id) }}" class="btn btn-xs text-danger p-0 btn-delete" data-toggle="modal" data-target="#modalDelete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                    
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $pelanggans->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection
@push('modals')
    <x-modal-delete />
@endpush
