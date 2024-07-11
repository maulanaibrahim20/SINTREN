<?php

namespace App\Http\Controllers\WEB\Operator\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\User\Penyuluh\CreateRequest;
use App\Http\Requests\Operator\User\Penyuluh\UpdateRequest;
use App\Models\Penyuluh\Penyuluh;
use App\Models\User;
use App\Models\Role;
use App\Models\Wilayah\Desa;
use App\Models\Wilayah\Kecamatan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PenyuluhController extends Controller
{
    protected $user;
    protected $penyuluh;
    protected $kecamatan;
    protected $desa;

    public function __construct(User $user, Penyuluh $penyuluh, Kecamatan $kecamatan, Desa $desa)
    {
        $this->user = $user;
        $this->penyuluh = $penyuluh;
        $this->kecamatan = $kecamatan;
        $this->desa = $desa;
    }
    public function index()
    {
        $data = [
            'title' => 'Data Pengguna Penyuluh',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Data Pengguna Penyuluh',
            'button_create' => 'Tambah Data Pengguna',
            'users' => $this->penyuluh::orderBy('created_at', 'asc')->get(),
        ];
        return view('operator.pages.user.penyuluh.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data Pengguna Penyuluh',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pengguna Penyuluh',
            'breadcrumb_active' => 'Tambah Data Pengguna Penyuluh',
            'kecamatan' => $this->kecamatan::all(),
            'selected' => $this->penyuluh::pluck('kecamatan_id')->toArray(),
        ];
        return view('operator.pages.user.penyuluh.create', $data);
    }
    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = $this->user->create($request->all() + [
                'username' => Str::slug($request->name),
                'password' => bcrypt('password'),
                'role_id' => Role::PENYULUH,
            ]);
            $this->penyuluh->create($request->all() + [
                'user_id' => $user->id,
                'kecamatan_id' => $request->kecamatan,
                'createdBy' => Auth::user()->id,
            ]);

            DB::commit();
            return redirect('/operator/user/penyuluh')->with('success', 'User Penyuluh Berhasil Ditambahkan!');
        } catch (\Exception $er) {
            DB::rollback();
            return back()->with('error', 'Gagal Menambahkan User Penyuluh' . $er->getMessage());
        }
    }

    public function show($id)
    {
        $data = [
            'user' => $this->penyuluh::findOrFail(decrypt($id)),
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pengguna Penyuluh',
            'breadcrumb_active' => 'Detail Data Pengguna Penyuluh',
        ];
        return view('operator.pages.user.penyuluh.show', $data);
    }

    public function edit($id)
    {
        $user = $this->penyuluh::findOrFail(decrypt($id));
        $desa = $this->desa::orderBy('name', 'asc')->get();
        $kec = $this->kecamatan::orderBy('name', 'asc')->get();
        $data = [
            'kecamatan' => $this->kecamatan::where('id', $user->kecamatan_id)->get(),
            'selected_kec' => $user->kecamatan_id,
            'selected_desa' => $user->desa_id,
            'title' => 'Edit Data Pengguna Penyuluh',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pengguna Penyuluh',
            'breadcrumb_active' => 'Edit Data Pengguna Penyuluh',
        ];
        return view('operator.pages.user.penyuluh.update', $data, compact('user', 'desa', 'kec'));
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $decryptedId = Crypt::decrypt($id); // Pastikan ID terdekripsi dengan benar
            $penyuluh = $this->penyuluh->findOrFail($decryptedId);
            $user = $penyuluh->user;

            $penyuluh->update($request->all() + [
                'updated_at' => Carbon::now(),
                'desa_id' => $request->desa,
                'kecamatan_id' => $request->kecamatan
            ]);

            $user->update($request->all() + [
                'updated_at' => Carbon::now(),
            ]);

            DB::commit();
            return redirect('/operator/user/penyuluh')->with('success', 'User Penyuluh Berhasil Diubah!');
        } catch (\Exception $er) {
            DB::rollback();
            return back()->with('error', 'Gagal Mengubah User Penyuluh: ' . $er->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $user = $this->penyuluh->findOrFail($id);
            $user->delete();
            $user->user->delete();
            DB::commit();
            Alert::success('success', 'User penyuluh berhasil dihapus!');
            return redirect('/operator/user/penyuluh')->with('success', 'User Penyuluh Berhasil Dihapus!');
        } catch (\Exception $er) {
            DB::rollback();
            Alert::error('error', 'User penyuluh gagal dihapus!' . $er->getMessage());
            return back()->with('error', 'Gagal Menghapus User Penyuluh' . $er->getMessage());
        }
    }
}
