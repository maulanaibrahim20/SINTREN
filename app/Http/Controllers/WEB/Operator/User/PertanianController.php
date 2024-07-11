<?php

namespace App\Http\Controllers\WEB\Operator\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\User\Pertanian\CreateRequest;
use App\Http\Requests\Operator\User\Pertanian\UpdatedRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Pertanian\Pertanian;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class PertanianController extends Controller
{
    protected $user;
    protected $pertanian;

    public function __construct(User $user, Pertanian $pertanian)
    {
        $this->user = $user;
        $this->pertanian = $pertanian;
    }
    public function index()
    {
        $data = [
            'title' => 'Data Pengguna Pertanian',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Data Pengguna Pertanian',
            'add_button' => 'Tambah Data Pengguna',
            'users' => $this->pertanian::orderBy('created_at', 'asc')->get(),
        ];
        return view('operator.pages.user.pertanian.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Buat Akun Pengguna Pertanian',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pengguna Pertanian',
            'breadcrumb_active' => 'Tambah Pengguna Pertanian',
        ];
        return view('operator.pages.user.pertanian.create', $data);
    }

    public function store(CreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->user->create($request->all() + [
                'username' => Str::slug($request->name),
                'password' => bcrypt('password'),
                'role_id' => Role::PERTANIAN,
            ]);
            $this->pertanian->create($request->all() + [
                'user_id' => $user->id,
            ]);

            DB::commit();
            $successMessage = "Data User Pertanian Berhasil Ditambahkan!\n\nUsername: {$user->username}\nPassword: password";
            return redirect('/operator/user/pertanian')->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollback();
            $errorMessage = 'Gagal Menambahkan Data: ' . $e->getMessage();
            return back()->withInput()->withErrors($errorMessage);
        }
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Buat Akun Pengguna Pertanian',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pengguna Pertanian',
            'breadcrumb_active' => 'Edit Pengguna Pertanian',
            'user' => $this->pertanian->findOrFail(decrypt($id)),
        ];
        return view('operator.pages.user.pertanian.update', $data);
    }

    public function show($id)
    {
        $data = [
            'title' => 'Detail Data Pengguna Pertanian',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pengguna Pertanian',
            'breadcrumb_active' => 'Detail Data Pengguna Pertanian',
            'user'  => $this->pertanian->findOrFail(decrypt($id)),
        ];
        return view('operator.pages.user.pertanian.show', $data);
    }

    public function update(UpdatedRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->pertanian->findOrFail(decrypt($id));
            $user->update($request->all() + [
                'updated_at' => now(),
            ]);
            $user->user->update($request->all() + [
                'updated_at' => now(),
            ]);
            DB::commit();
            return redirect('/operator/user/pertanian')->with('success', 'Success data berhasil diubah!');
        } catch (\Exception $er) {
            DB::rollback();
            $errorMessage = 'Gagal Menambahkan Data: ' . $er->getMessage();
            return back()->withInput()->withErrors($errorMessage);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = $this->pertanian->findOrFail($id);
            $user->user->delete();
            $user->delete();

            DB::commit();

            return back()->with('success', 'Data Berhasil Dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Data GagalDihapus!');
        }
    }
}
