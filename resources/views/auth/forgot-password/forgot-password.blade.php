<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} | Lupa Password</title>
    <link rel="stylesheet" href="{{ asset('') }}">
    <link rel="stylesheet" href="{{ asset('/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/adminlte/dist/css/adminlte.min.css') }}">
    @stack('styles')
</head>
<body>
    <div class="login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href=""><b>Madu</b>Jaya</a>
            </div>
            <div class="card">
                @if (session('success'))
					<div class="alert alert-dismissible alert-success" role="alert">
						{{ Session::get('success') }}
					</div>
			    @endif
                @if (session('error'))
					<div class="alert alert-dismissible alert-success" role="alert">
						{{ Session::get('error') }}
					</div>
			    @endif
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Masukkan email yang terdaftar</p>
                    <form action="{{ route('forgot-password') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-6"></div>
                            <div class="col-6 pl-2">
                                <div class="input-group mt-2">
                                    <button class="btn btn-primary" type="submit">Kirim Permintaan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @stack('modals')
    <script src="{{ asset('/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/adminlte/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
