@extends('layouts.main', ['title' => 'Pelanggan'])
@section('title-content')
    <i class="fas fa-user-tie mr-2"></i> Pelanggan
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <form action="{{ route('pelanggan.update', $pelanggan->id) }}" method="POST" class="card card-orange card-outline">
                @csrf
                @method('PUT')
                <div class="card-header">
                    <h3 class="card-title">Ubah Pelanggan</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Nama Lengkap</label>
                        {{-- <x-input name="name" type="text" value="{{ $pelanggan->name }}" /> --}}
                        <input type="text" name="name" value="{{ $pelanggan->name }}" class="form-control @error('name') is-invalid @enderror" autofocus>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Alamat</label>
                        <x-text-area name="alamat" value="{{ $pelanggan->alamat }}" />
                    </div>
                    <div class="form-group">
                        <label for="">Nomor Telepon / HP</label>
                        <x-input name="nomor_tlp" type="number" value="{{ $pelanggan->nomor_tlp }}" />
                    </div>
                </div>
                <div class="card-footer form-inline">
                    <button type="submit" class="btn btn-primary">Update Pelanggan</button>
                    <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary ml-auto">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection