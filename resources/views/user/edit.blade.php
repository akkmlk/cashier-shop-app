@extends('layouts.main', ['title' => 'User'])
@section('title-content')
    <i class="fas fa-user-tie mr-2"></i> User
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <form action="{{ route('user.update', $user->id) }}" method="POST" class="card card-orange card-outline">
                @csrf
                @method('PUT')
                <div class="card-header">
                    <h3 class="card-title">Ubah User</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Nama Lengkap</label>
                        {{-- <x-input name="name" type="text" value="{{ $user->name }}" /> --}}
                        <input type="text" name="name" value="{{ $user->name }}" class="form-control @error('name') is-invalid @enderror" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="">Username</label>
                        <x-input name="username" type="text" value="{{ $user->username }}" />
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <x-input name="email" type="text" value="{{ $user->email }}" />
                    </div>
                    <div class="form-group">
                        <label for="">Role</label>
                        <x-select name="role" value="{{ $user->role }}" :options="[
                            // ['', 'Pilih'], ['petugas', 'Petugas'], ['admin', 'Administrator'],
                            ['value' => '', 'option' => 'Pilih :'],
                            ['value' => 'petugas', 'option' => 'Petugas'],
                            ['value' => 'admin', 'option' => 'Administrator'],
                        ]" />
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <x-input name="password" type="password" />
                    </div>
                    <div class="form-group">
                        <label for="">Konfirmasi Password</label>
                        <x-input name="password_confirmation" type="password" />
                    </div>
                </div>
                <div class="card-footer form-inline">
                    <button type="submit" class="btn btn-primary">Update User</button>
                    <a href="{{ route('user.index') }}" class="btn btn-secondary ml-auto">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection