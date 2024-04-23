@extends('layouts.main', ['title' => 'Profile'])
@section('title-content')
    <i class="fas fa-user mr-2"></i>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <form action="{{ route('profile.update') }}" class="card card-orange card-outline" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Nama Lengkap</label>
                        <x-input name="name" type="text" :value="$user->name" />
                    </div>
                    <div class="form-group">
                        <label for="">Username</label>
                        <x-input name="username" type="text" :value="$user->username" />
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <x-input name="password" type="password" />
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <x-input name="password_confirmation" type="password" />
                    </div>
                </div>
                <div class="card-footer form-inline">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary ml-auto">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection