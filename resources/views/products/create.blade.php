@extends('layouts.main', ['title' => 'Product'])
@section('title-content')
    <i class="fas fa-list mr-2"></i> Product
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <form action="{{ route('product.store') }}" class="card card-orange card-outline" method="POST">
                @csrf
                <div class="card-header">
                    <h3 class="card-title">Buat Product Baru</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Kode Product</label>
                        {{-- <x-input name="kode_product" type="text" /> --}}
                        <input type="text" name="kode_product" class="form-control @error('kode_product') is-invalid @enderror" autofocus>
                        @error('kode_product')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Nama Product</label>
                        <x-input name="nama_product" type="text" />
                    </div>
                    <div class="form-group">
                        <label for="">Harga</label>
                        <x-input name="harga" type="number" />
                    </div>
                    <div class="form-group">
                        <label for="">Kategori</label>
                        <x-select name="kategori_id" :options="$kategoris" />
                    </div>
                <div class="card-footer form-inline">
                    <button type="submit" class="btn btn-primary">Simpan Product</button>
                    <a href="{{ route('product.index') }}" class="btn btn-secondary ml-auto">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection