<?php

namespace App\Http\Controllers\WEB\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class VerificationController extends Controller
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function __invoke(Request $request)
    {
        DB::beginTransaction();

        $user = $this->user->whereEmail($request->email)->first();
        if (!$user) {
            return redirect('/login')->with('error', 'Maaf Akun Anda Tidak Terdaftar!');
        }

        $request->merge([
            "password" => ""
        ]);

        try {
            $status = Password::reset(
                $request->only('email', 'token', 'password'),
                function ($user, $password) {
                    $user->forceFill([
                        'created_at' => Carbon::now()
                    ]);
                    $user->save();
                    
                    event(new PasswordReset($user));
                }
            );

            DB::commit();
            if ($status == Password::PASSWORD_RESET) {
                return redirect('/login')->with('success', 'Selamat Akun Anda Berhasil di verifikasi');
            } else {
                return redirect('/login')->with('error', 'Maaf Token Anda Kadaluarsa!');
            }
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect('/login')->with('error', 'Maaf Token Anda Kadaluarsa!' . $e->getMessage());
        }
    }
}
