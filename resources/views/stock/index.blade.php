@extends('layouts.main', ['title' => 'Stock'])
@section('title-content')
    <i class="fas fa-pallet mr-2"></i> Stock
@endsection
@section('content')
    @if (session('store') == 'success')
        <x-alert type="success" >
            <strong>Berhasil dibuat!</strong>  Stock berhasil dibuat.
        </x-alert>
    @endif
    @if (session('destroy') == 'success')
        <x-alert type="success" >
            <strong>Berhasil dihapus!</strong>  Stock berhasil dihapus.
        </x-alert>
    @endif
    <div class="card card-orange card-outline">
        <div class="card-header form-inline">
            <a href="{{ route('stock.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Stock
            </a>
            <form action="" method="GET" class="ml-auto">
                <div class="input-group">
                    <input type="date" class="form-control" name="search" value="{{ request()->search }}">
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
                        <th>Nama Product</th>
                        <th>Jumlah</th>
                        <th>Nama Suplier</th>
                        <th>Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stocks as $key => $stock)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $stock->nama_product }}</td>
                            <td>{{ $stock->jumlah }}</td>
                            <td>{{ $stock->nama_suplier }}</td>
                            <td>{{ $stock->tanggal }}</td>
                            <td class="text-right">
                                <button type="button" data-url="{{ route('stock.destroy', $stock->id) }}" class="btn btn-xs text-danger p-0 btn-delete" data-toggle="modal" data-target="#modalDelete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $stocks->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection
@push('modals')
    <x-modal-delete />
@endpush
