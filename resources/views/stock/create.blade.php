@extends('layouts.main', ['title' => 'Stock'])
@section('title-content')
    <i class="fas fa-pallet mr-2"></i> Stock
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <form action="{{ route('stock.store') }}" class="card card-orange card-outline" method="POST">
                @csrf
                <div class="card-header">
                    <h3 class="card-title">Buat Stock Baru</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Nama Product</label>
                        <div class="input-gorup d-flex">
                            {{-- <x-input name="nama_product" id="namaProduct" type="text" disabled /> --}}
                            <input type="text" name="nama_product" value="{{ old('nama_product') }}" id="namaProduct" class="form-control @error('nama_product') is-invalid @enderror" disabled>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCari">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        @error('nama_product')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <input type="hidden" name="product_id" id="productId">
                    </div>
                    <div class="form-group">
                        <label for="">Jumlah</label>
                        <x-input name="jumlah" type="text" />
                    </div>
                    <div class="form-group">
                        <label for="">Nama Suplier</label>
                        <x-input name="nama_suplier" type="text" />
                    </div>
                <div class="card-footer form-inline">
                    <button type="submit" class="btn btn-primary">Simpan Stock</button>
                    <a href="{{ route('stock.index') }}" class="btn btn-secondary ml-auto">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('modals')
    <div class="modal fade" id="modalCari" data-backdrop="static" data-keyword="false" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cari Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" id="formSearch" method="GET" class="input-group">
                        <input type="text" class="form-control" id="search">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    <table class="table table-stripper table-hover table-sm mt-3">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Product</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="resultProduct">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        $(function() {
            $('#formSearch').submit(function(event) {
                event.preventDefault();
                let search = $(this).find('#search').val();
                if (search.length >= 3) {
                    fetchProduct(search);
                } 
            });
        })

        function fetchProduct(search) {
            let url = "{{ route('stock.product') }}?search=" + search;
            $.getJSON(url, function(result) {
                $('#resultProduct').html('');

                result.forEach((product, index) => {
                    let row = `<tr>`;
                        row += `<td>${ index + 1 }</td>`;
                        row += `<td>${ product.nama_product }</td>`;
                        row += `<td class="text-right">`;
                        row += `<button type="button" class="btn btn-xs btn-success" onclick="addProduct('${product.id}', '${product.nama_product}')">`;
                        row += `Add`;
                        row += `</button>`;
                        row += `</td>`;
                        row += `</tr>`;
                        $('#resultProduct').append(row);
                });
            });
        }

        function addProduct(id, nama_product) {
            var nama = $('#namaProduct').val(nama_product)
            $('#productId').val(id)
            $('#modalCari').modal('hide')
        }
    </script>
@endpush