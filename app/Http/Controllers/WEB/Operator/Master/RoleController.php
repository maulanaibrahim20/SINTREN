<?php

namespace App\Http\Controllers\WEB\Operator\Master;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }
    public function index()
    {
        $data = [
            'title' => 'role',
        ];
        $role = $this->role::all();
        return view('operator.pages.master.role.index', $data, compact('role'));
    }
}
