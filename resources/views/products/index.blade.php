@extends('layouts.main', ['title' => 'Products'])
@section('title-content')
    <i class="fas fa-list mr-2"></i> Products
@endsection
@section('content')
    @if (session('store') == 'success')
        <x-alert type="success" >
            <strong>Berhasil dibuat!</strong>  Product berhasil dibuat.
        </x-alert>
    @endif
    @if (session('update') == 'success')
        <x-alert type="success" >
            <strong>Berhasil diupdate!</strong>  Product berhasil diupdate.
        </x-alert>
    @endif
    @if (session('destroy') == 'success')
        <x-alert type="success" >
            <strong>Berhasil dihapus!</strong>  Product berhasil dihapus.
        </x-alert>
    @endif
    <div class="card card-orange card-outline">
        <div class="card-header form-inline">
            <a href="{{ route('product.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Product
            </a>
            <form action="" method="GET" class="ml-auto">
                <div class="input-group">
                    <input type="search" class="form-control" name="search" value="{{ request()->search }}" placeholder="Cari Product">
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
                        <th>Kode</th>
                        <th>Nama Product</th>
                        <th>Ketgori</th>
                        <th>Harga</th>
                        <th>Stock</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $key => $product)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $product->kode_product }}</td>
                            <td>{{ $product->nama_product }}</td>
                            <td>{{ $product->nama_kategori }}</td>
                            <td>{{ $product->harga }}</td>
                            <td>{{ $product->stock }}</td>
                            <td class="text-right">
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-xs text-success p-0 mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" data-url="{{ route('product.destroy', $product->id) }}" class="btn btn-xs text-danger p-0 btn-delete" data-toggle="modal" data-target="#modalDelete">
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
            {{ $products->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection
@push('modals')
    <x-modal-delete />
@endpush
