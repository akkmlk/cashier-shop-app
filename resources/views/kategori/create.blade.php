@extends('layouts.main', ['title' => 'Kategori'])
@section('title-content')
    <i class="fas fa-list mr-2"></i> Kategori
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <form action="{{ route('kategori.store') }}" class="card card-orange card-outline" method="POST">
                @csrf
                <div class="card-header">
                    <h3 class="card-title">Buat Kategori Baru</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Nama Kategori</label>
                        {{-- <x-input name="nama_kategori" type="text" /> --}}
                        <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" autofocus>
                        @error('nama_kategori')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                <div class="card-footer form-inline">
                    <button type="submit" class="btn btn-primary">Simpan Kategori</button>
                    <a href="{{ route('kategori.index') }}" class="btn btn-secondary ml-auto">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection