<?php

namespace App\Http\Controllers\WEB\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function index()
    {
        return view('auth.forgot_password.forgot_password');
    }

    public function sendEmail(ResetPasswordRequest $request)
    {
        $user = $this->user->whereEmail($request->email)->first();
        if (!$user) {
            return back()->with('error', 'Akun tidak ditemukan');
        }
        $status = Password::sendResetLink($request->only('email'));
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('success', 'Periksa Email Anda Untuk Mendapatkan Link Reset Password');
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)]
        ]);
    }
}
