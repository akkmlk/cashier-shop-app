<?php

namespace App\Http\Controllers\ForgotPassword;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        return view('auth.forgot-password.forgot-password');
    }

    public function forgot(Request $request)
    {
        $email = $request->email;
        $status = Password::sendResetLink(
            ['email' => $email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Silahkan periksa email anda dan akses email yang didapat');
        } else {
            return back()->with('error', 'Mohon untuk periksa koneksi dan coba lagi');
        }
    }

    public function indexResetPassword($token)
    {
        return view('auth.reset-password.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'password.confirmed' => "Konfirmasi password tidak cocok dengan password",
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function(User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('resetPassword', 'Reset password berhasil');
        } else {
            return back()->with('error', 'Mohon untuk periksa koneksi dan coba lagi');
        }
    }
}
