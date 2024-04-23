<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} | Login</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/adminlte/dist/css/adminlte.min.css') }}">
    @stack('styles')
</head>
<body class="login-page">
	<div class="login-box">
		<div class="login-logo">
			<a href="/"><b>Madu</b>Jaya</a>
		</div>
		<div class="card">
			@if (session('resetPassword'))
					<div class="alert alert-dismissible alert-success" role="alert">
						{{ Session::get('resetPassword') }}
					</div>
			@endif
			<div class="card-body login-card-body">
				<p class="login-box-msg">Masuk untuk memulai sesi anda</p>
				<form action="{{ route('login.login') }}" method="POST">
					@csrf
					<div class="input-group">
						<input type="text" name="username" class="form-control @error('username') is-invalid @enderror" placeholder="Username" value="{{ old('username') }}">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-envelope"></span>
							</div>
						</div>
						@error('username')
							<div class="invalid-feedback">
								{{ $message }}
							</div>
						@enderror
					</div>
					<div class="input-group mt-3">
						<input type="password" name="password" id="inputPassword" class="form-control @error('password') is-invalid @enderror" placeholder="Password" value="{{ old('password') }}">
						<div class="input-group-append">
							<div class="input-group-text">
								<div onclick="showHidePassword()">
									<i class="fas fa-eye mr-2" style="display: none;" id="eye"></i>
									<i class="fas fa-eye-slash mr-2" id="eyeSlash"></i>
								</div>
								<span class="fas fa-lock"></span>
							</div>
						</div>
						@error('password')
							<div class="invalid-feedback">
								{{ $message }}
							</div>
						@enderror
					</div>
					<div class="row mt-3">
						<div class="col-7">
							
						</div>
						<div class="col-5 text-right">
							<a href="{{ route('forgot-password.index') }}" class="text-dark text-decoration-none">Lupa password?</a>
						</div>
						<div class="col-8">
							<div class="icheck-primary">
								<input type="checkbox" name="remember" id="remember">
								<label for="remember">Ingat Saya</label>
							</div>
						</div>
						<div class="col-4">
							<button type="submit" class="btn btn-primary btn-lock ml-4">Sign In</button>
						</div>
					</div>
				</form>
				{{-- <p class="text-dark">Belum punya akun? <a href="{{ route('register') }}" class="text-dark">Daftar</a></p> --}}
			</div>
		</div>
	</div>
    @stack('modals')
    <script src="{{ asset('/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/adminlte/dist/js/adminlte.min.js') }}"></script>
		<script>
			function showHidePassword() {
				const passCheck = document.getElementById('inputPassword');
				const eye = document.getElementById('eye');
				const eyeSlash = document.getElementById('eyeSlash');

				if (passCheck.type == 'password') {
					passCheck.type = 'text';
					eye.style.display = 'block';
					eyeSlash.style.display = 'none';
				} else {
					passCheck.type = 'password';
					eye.style.display = 'none';
					eyeSlash.style.display = 'block';
				}
			}
		</script>
    @stack('js')
</body>

</html>
