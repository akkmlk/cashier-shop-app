@extends('layouts.main', ['title' => 'Promo'])
@section('title-content')
    <i class="fas fa-list mr-2"></i> Promo
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <form action="{{ route('promo.store') }}" class="card card-orange card-outline" method="POST">
                @csrf
                <div class="card-header">
                    <h3 class="card-title">Buat Promo Baru</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Nama Promo</label>
                        <x-input name="name" type="text" placeholder="Nama"/>
                    </div>
                    <div class="form-group">
                        <label for="">Deskripsi</label>
                        <textarea name="description" cols="25" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Type Promo</label>
                        {{-- <x-select name="type" id="type" :options="$typePromo" /> --}}
                        <select name="type" class="form-control @error('type') is-invalid @enderror" id="type" >
                            @foreach ($typePromo as $option)
                                <option value="{{ old('type', $option['value']) }}">{{ $option['option'] }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Beli</label>
                        @php
                            $buy = [
                                ['value' => '', 'option' => 'Pilih jumlah'],
                                ['value' => 1, 'option' => 1],
                                ['value' => 2, 'option' => 2],
                                ['value' => 3, 'option' => 3],
                                ['value' => 4, 'option' => 4],
                                ['value' => 5, 'option' => 5],
                            ]
                        @endphp
                        <select name="buy" class="form-control @error('buy') is-invalid @enderror" id="buy" disabled >
                            @foreach ($buy as $option)
                                <option value="{{ old('buy', $option['value']) }}">{{ $option['option'] }}</option>
                            @endforeach
                        </select>
                        @error('buy')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Dapat</label>
                        <select name="get" class="form-control @error('get') is-invalid @enderror" id="get" disabled >
                            @foreach ($buy as $option)
                                <option value="{{ old('get', $option['value']) }}">{{ $option['option'] }}</option>
                            @endforeach
                        </select>
                        @error('get')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Diskon</label>
                        <input type="number" id="discount" name="discount" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount') }}" disabled>
                        @error('discount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Product nya</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="productNames" placeholder="Pilih product nya" readonly>
                            <input type="hidden" class="form-control" name="products" id="products" placeholder="Pilih product nya" readonly>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-info" id="btnSearch" data-toggle="modal" data-target="#modalSearch">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer form-inline">
                    <button type="submit" class="btn btn-primary">Simpan Promo</button>
                    <a href="{{ route('promo.index') }}" class="btn btn-secondary ml-auto">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('modals')
    <div class="modal fade" id="modalSearch" data-backdrop="static" data-keyword="false" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <form action="" id="formSearch" method="GET" class="input-group">
                        <input type="text" class="form-control" id="search">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form> --}}
                    <table class="table table-stripper table-hover table-sm mt-3">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Product</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($products->isEmpty())
                                <tr>
                                    <td></td>
                                    <td>Semua menu sudah memiliki promo</td>
                                    <td></td>
                                </tr>
                            @else 
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            <label for="">{{ $product->nama_product }}</label>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="product" id="cbProduct{{ $product->id }}" data-name="{{ $product->nama_product }}" value="{{ $product->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" id="btnSave">Pilih</button>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('scripts')
    <script>
        
        document.addEventListener("DOMContentLoaded", function() {
            // Function untuk validasi type promo
            var type = document.getElementById('type');
            var buy = document.getElementById('buy');
            var get = document.getElementById('get');
            var discount = document.getElementById('discount');
            type.addEventListener('change', function() {
                if (type.value == 'buyget') {
                    buy.disabled = false;
                    get.disabled = false;
                    discount.disabled = true;
                } else if (type.value == 'discount') {
                    buy.disabled = true;
                    get.disabled = true;
                    discount.disabled = false;
                } else {
                    buy.disabled = true;
                    get.disabled = true;
                    discount.disabled = true;
                }
            });

            // Function untuk memilih beberapa product untuk di promokan
            var selectedProducts = [];
            var selectedProductsName = [];
            document.querySelectorAll("input[type=checkbox]").forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        selectedProducts.push(this.value);
                        selectedProductsName.push(checkbox.getAttribute('data-name'));
                    } else {
                        var index = selectedProducts.indexOf(this.value);
                        if (index !== -1) {
                            selectedProducts.splice(index, 1);
                            selectedProductsName.splice(index, 1);
                        }
                    }
                    document.getElementById('products').value = selectedProducts.join(", ");
                    document.getElementById('productNames').value = selectedProductsName.join(", ");
                });
            });
    
            document.getElementById('btnSave').addEventListener('click', function() {
                $('#modalSearch').modal('hide');
            });
        });
    </script>
@endpush