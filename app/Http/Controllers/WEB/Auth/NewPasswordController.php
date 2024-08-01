<?php

namespace App\Http\Controllers\WEB\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class NewPasswordController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(Request $request)
    {
        if (!$request->has('token')) {
            return redirect('/login');
        }

        if (!$request->has('email')) {
            return redirect('/login');
        }

        return view('auth.new_password.index');
    }

    public function process(NewPasswordRequest $request)
    {
        DB::beginTransaction();

        $user = $this->user->whereEmail($request->email)->first();
        if (!$user) {
            return redirect('/login')->with('error', 'Maaf Akun Anda Tidak Terdaftar!');
        }
        try {
            $status = Password::reset(
                $request->only('email', 'password', 'confirm_password', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => bcrypt($password),
                    ]);
                    $user->save();
                    event(new PasswordReset($user));
                }
            );

            DB::commit();
            if ($status == Password::PASSWORD_RESET) {
                return redirect('/login')->with('success', 'Selamat password anda berhasil diubah!');
            } else {
                return redirect('/login')->with('error', 'Maaf token sudah kedaluwarsa, silahkan lakukan reset password!');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error' . $e->getMessage());
        }
    }
}
