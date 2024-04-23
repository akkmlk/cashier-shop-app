<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} | Registration</title>
	<link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/adminlte/dist/css/adminlte.min.css') }}">
    @stack('styles')
</head>
<body class="login-page">
	<div class="login-box">
		<div class="login-logo">

			<a href="">Daftar <b>Madu</b> Jaya</a>
		</div>
		<div class="card">
			 <div class="card-body login-card-body">
				<p class="login-box-msg">Daftar untuk memulai sesi anda</p>
				<form action="{{ route('register.register') }}" method="POST">
					@csrf
					<div class="input-group">
						<input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Lengkap">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-user"></span>
							</div>
						</div>
						@error('name')
							<div class="invalid-feedback">
								{{ $message }}
							</div>
						@enderror
					</div>
					<div class="input-group mt-3">
						<input type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Nama Lengkap">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-user"></span>
							</div>
						</div>
						@error('username')
							<div class="invalid-feedback">
								{{ $message }}
							</div>
						@enderror
					</div>
					<div class="input-group mt-3">
						<input type="text" class="form-control @error('password') is-invalid @enderror" placeholder="Nama Lengkap">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-user"></span>
							</div>
						</div>
						@error('username')
							<div class="invalid-feedback">
								{{ $message }}
							</div>
						@enderror
					</div>
				</form>
			 </div>
		</div>
	</div>
	@stack('modals')
	<script src="{{ asset('/adminlte/plugins/jquery/jquery.min.js') }}"></script>
	<script src="{{ asset('/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('/adminlte/dist/js/adminlte.min.js') }}"></script>
	@stack('js')
</body>
</html>
