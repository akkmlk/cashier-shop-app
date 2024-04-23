<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} | Reset Password</title>
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
                @if (session('error'))
					<div class="alert alert-dismissible alert-success" role="alert">
						{{ Session::get('error') }}
					</div>
			    @endif
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Perbaharui password</p>
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="text" name="token" style="display: none;" value="{{ request()->token }}">
                        <input type="text" name="email" style="display: none;" value="{{ request()->email }}">
                        <div class="input-group">
                            <input type="password" id="inputPassword" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" placeholder="Password baru">
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
                        <div class="input-group mt-2">
                            <input type="password" id="inputPasswordConfirm" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" value="{{ old('password_confirmation') }}" placeholder="Konfirmasi Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <div onclick="showHidePasswordConfirm()">
                                        <i class="fas fa-eye mr-2" style="display: none;" id="eyeConfirm"></i>
                                        <i class="fas fa-eye-slash mr-2" id="eyeSlashConfirm"></i>
                                    </div>
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            @error('password_confirmation')
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

        function showHidePasswordConfirm() {
            const passConfirmCheck = document.getElementById('inputPasswordConfirm');
            const eyeConfirm = document.getElementById('eye');
            const eyeSlashConfirm = document.getElementById('eyeSlash');

            if (passConfirmCheck.type == 'password') {
                passConfirmCheck.type = 'text';
                eyeConfirm.style.display = 'block';
                eyeSlashConfirm.style.display = 'none';
            } else {
                passConfirmCheck.type = 'password';
                eyeConfirm.style.display = 'none';
                eyeSlashConfirm.style.display = 'block';
            }
        }
    </script>
</body>
</html>
