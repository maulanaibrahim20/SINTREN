<?php

namespace App\Http\Controllers\WEB\Operator;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user;
    protected $role;

    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }
    public function index()
    {
        $data = [
            'title' => 'Data Pengguna',
        ];
        $users = $this->user->all();
        return view('operator.pages.user.index', compact('users'), $data);
    }

    public function create()
    {
        $role = $this->role::where('name', '!=', 'operator')->get();
        return view('operator.pages.user.create', compact('role'));
    }
}
