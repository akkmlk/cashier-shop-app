@extends('layouts.main', ['title' => 'Promo'])
@section('title-content')
    <i class="fas fa-list mr-2"></i> Promo
@endsection
@section('content')
    @if (session('store') == 'success')
        <x-alert type="success" >
            <strong>Berhasil dibuat!</strong> Promo berhasil dibuat.
        </x-alert>
    @endif
    @if (session('update') == 'success')
        <x-alert type="success" >
            <strong>Berhasil diupdate!</strong> Promo berhasil diupdate.
        </x-alert>
    @endif
    @if (session('destroy') == 'success')
        <x-alert type="success" >
            <strong>Berhasil dihapus!</strong> Promo berhasil dihapus.
        </x-alert>
    @endif
    <div class="card card-orange card-outline">
        <div class="card-header form-inline">
            <a href="{{ route('promo.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Promo
            </a>
            <form action="" method="GET" class="ml-auto">
                <div class="input-group">
                    <input type="search" class="form-control" name="search" value="{{ request()->search }}" placeholder="Cari Promo">
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
                        <th>Nama Promo</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($promos as $key => $promo)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $promo->name }}</td>
                            <td>{{ $promo->type }}</td>
                            <td>
                                <p class="{{ $promo->active == 1 ? 'badge badge-success' : 'badge badge-danger' }} m-0">{{ $promo->active == 1 ? 'Aktif' : 'Tidak Aktif' }}</p>
                            </td>
                            <td class="text-right">
                                <button type="button" class="btn btn-xs text-info" id="btnShow" data-toggle="modal" data-target="#modalShow{{ $promo->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('promo.edit', $promo->id) }}" class="btn btn-xs text-success p-0 mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" data-url="{{ route('promo.destroy', $promo->id) }}" class="btn btn-xs text-danger p-0 btn-delete" data-toggle="modal" data-target="#modalDelete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                            
                            <div class="modal fade" id="modalShow{{ $promo->id }}" data-backdrop="static" data-keyword="false" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Promo</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="" class="m-0 p-0">Nama Promo</label>
                                                <p class="m-0 p-0">{{ $promo->name }}, <span class="text-capitalize font-weight-bold">-Type : {{ $promo->type }}</span></p>
                                            </div>
                                            <div class="form-group mt-0">
                                                <label for="" class="m-0 p-0">Deskripsi</label>
                                                <p class="m-0 p-0">{{ $promo->description }}</p>
                                            </div>
                                            <div class="d-flex justify-content-around text-center">
                                                <div class="form-group">
                                                    <label for="" class="m-0 p-0">Beli Berapa</label><br>
                                                    <p class="m-0 {{ $promo->buy != null ? 'badge badge-success' : '' }}">{{ $promo->buy != null ? $promo->buy : '-' }}</p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="m-0 p-0">Dapat Berapa</label><br>
                                                    <p class="m-0 {{ $promo->get != null ? 'badge badge-success' : '' }}">{{ $promo->get != null ? $promo->get : '-' }}</p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="m-0 p-0">Diskon Berapa</label><br>
                                                    <p class="m-0 {{ $promo->discount != null ? 'badge badge-success' : '' }}">{{ $promo->discount != null ? $promo->discount . '%' : '-' }}</p>
                                                </div>
                                            </div>
                                            <label class="m-0 mb-1">Produk sedang promo</label>
                                            @foreach ($promo->products as $product)
                                                <div class="d-flex justify-content-between">
                                                    <div class="form-group mt-0">
                                                        <label for="" class="m-0 p-0">Nama</label>
                                                        <p class="m-0 p-0">{{ $product->nama_product }}</p>
                                                    </div>
                                                    <div class="form-group mt-0">
                                                        <label for="" class="m-0 p-0">Kode</label>
                                                        <p class="m-0 p-0">{{ $product->kode_product }}</p>
                                                    </div>
                                                    <div class="form-group mt-0">
                                                        <label for="" class="m-0 p-0">Harga</label>
                                                        <p class="m-0 p-0">{{ $product->harga }}</p>
                                                    </div>
                                                    <div class="form-group mt-0">
                                                        <label for="" class="m-0 p-0">Kategori</label>
                                                        <p class="m-0 p-0">{{ $product->kategori->nama_kategori }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
            {{ $promos->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection
@push('modals')
    <x-modal-delete />
@endpush
