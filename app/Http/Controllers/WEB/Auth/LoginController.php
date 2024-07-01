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
            return redirect(route('login.index'))->with('error', 'Periksa kembali username dan password anda');
        }
        if (!Hash::check($request->password, $user->password)) {
            return redirect(route('login.index'))->with('error', 'Periksa kembali username dan password anda');
        }
        if (Auth::attempt(["username" => $request->username, "password" => $request->password])) {
            $request->session()->regenerate();

            if ($user->role_id == Role::OPERATOR) {
                return redirect("/operator/dashboard")->with('success', 'Selamat anda berhasil login, selamat datang   ' . Auth::user()->name);
            } else if ($user->role_id == Role::PERTANIAN) {
                return redirect("/pertanian/dashboard")->with('success', 'Selamat anda berhasil login, selamat datang   ' . Auth::user()->name);
            } else if ($user->role_id == Role::UPTD) {
                return redirect("/uptd/dashboard")->with('success', 'Selamat anda berhasil login, selamat datang   ' . Auth::user()->name);
            } else if ($user->role_id == Role::PENYULUH) {
                return redirect("/penyuluh/dashboard")->with('success', 'Selamat anda berhasil login, selamat datang   ' . Auth::user()->name);
            } else if ($user->role_id == Role::PANGAN) {
                return redirect("/pangan/dashboard")->with('success', 'Selamat anda berhasil login, selamat datang   ' . Auth::user()->name);
            } else if ($user->role_id == Role::PASAR) {
                return redirect("/pasar/dashboard")->with('success', 'Selamat anda berhasil login, selamat datang   ' . Auth::user()->name);
            }
        }
        return back()->with('error', 'Gagal melakukan autentikasi');
    }
}
