<?php

namespace App\Http\Controllers\WEB\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class LoginController extends Controller
{

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function index()
    {
        return view('auth.login.login');
    }

    public function process(LoginRequest $request)
    {
        $user = $this->user->where('username', $request->username)->first();
        if (!$user) {
            Alert::error('Maaf Akun Anda Tidak Terdaftar');
            return redirect(route('login.index'))->with('error', 'Maaf Akun Anda Tidak Terdaftar');
        }
        if (!Hash::check($request->password, $user->password)) {
            Alert::error('Maaf Pasword Anda Salah!');
            return back()->with('error', 'Maaf Password Anda Salah!');
        }
        if (!$user->email_verified_at) {
            Alert::warning('Maaf Akun Anda Belum Terverifikasi');
            return back()->with('error', 'Maaf Akun Anda Belum Terverifikasi');
        }
        if (Auth::attempt(["username" => $request->username, "password" => $request->password])) {
            $request->session()->regenerate();

            if ($user->role_id == Role::OPERATOR) {
                Alert::success('success', 'Selamat anda berhasil login, selamat datang   ' . Auth::user()->name);
                return redirect("/operator/dashboard");
            } else if ($user->role_id == Role::PERTANIAN) {
                Alert::success('success', 'Selamat anda berhasil login, selamat datang   ' . Auth::user()->name);
                return redirect("/pertanian/dashboard");
            } else if ($user->role_id == Role::UPTD) {
                Alert::success('success', 'Selamat anda berhasil login, selamat datang   ' . Auth::user()->name);
                return redirect("/uptd/dashboard");
            } else if ($user->role_id == Role::PENYULUH) {
                Alert::success('success', 'Selamat anda berhasil login, selamat datang   ' . Auth::user()->name);
                return redirect("/penyuluh/dashboard");
            }
        }
        return back()->with('error', 'Gagal melakukan autentikasi');
    }
}
