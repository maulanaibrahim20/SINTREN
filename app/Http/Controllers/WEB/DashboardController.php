<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function operator()
    {
        $user = User::count();
        $verifiedUsersCount = User::whereNotNull('email_verified_at')->count();
        $unverifiedUsersCount = $user - $verifiedUsersCount;
        return view('operator.pages.dashboard.index', compact('user', 'verifiedUsersCount', 'unverifiedUsersCount'));
    }

    public function pertanian()
    {
        return view('pertanian.pages.dashboard.index');
    }

    public function uptd()
    {
        return view('uptd.pages.dashboard.index');
    }

    public function penyuluh()
    {
        return view('penyuluh.pages.dashboard.index');
    }
}
