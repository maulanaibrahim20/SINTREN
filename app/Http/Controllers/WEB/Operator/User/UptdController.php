<?php

namespace App\Http\Controllers\WEB\Operator\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\User\Uptd\CreateRequest;
use App\Http\Requests\Operator\User\Uptd\UpdateRequest;
use App\Models\Uptd\Uptd;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Wilayah\Kecamatan;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;

class UptdController extends Controller
{
    protected $user;
    protected $uptd;
    protected $kecamatan;
    public function __construct(User $user, Uptd $uptd, Kecamatan $kecamatan)
    {
        $this->user = $user;
        $this->uptd = $uptd;
        $this->kecamatan = $kecamatan;
    }
    public function index()
    {
        $data = [
            'title' => 'Data Pengguna UPTD',
            'users' => $this->uptd::orderBy('created_at', 'asc')->get(),
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Data Pengguna UPTD',
            'add_button' => 'Tambah Data Pengguna',
        ];
        return view('operator.pages.user.uptd.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data Pengguna UPTD',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pengguna UPTD',
            'breadcrumb_active' => 'Tambah Data Pengguna UPTD',
            'kecamatan' => $this->kecamatan::all(),
            'disabled' => $this->uptd::pluck('kecamatan_id')->toArray(),
        ];
        return view('operator.pages.user.uptd.create', $data);
    }
    public function store(CreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->user->create($request->all() + [
                'username' => Str::slug($request->name),
                'password' => bcrypt('password'),
                'role_id' => Role::UPTD,
            ]);
            $this->uptd->create($request->all() + [
                'user_id' => $user->id,
                'kecamatan_id' => $request->kecamatan
            ]);
            DB::commit();
            $successMessage = "Data User Uptd Berhasil Ditambahkan!\n\nUsername: {$user->username}\nPassword: password";
            return redirect('/operator/user/uptd')->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Data User Uptd gagal ditambahkan!' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $data = [
            'user' => $this->uptd::findOrFail(decrypt($id)),
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pengguna UPTD',
            'breadcrumb_active' => 'Detail Data Pengguna UPTD',

        ];
        return view('operator.pages.user.uptd.show', $data);
    }

    public function edit($id)
    {
        $data = [
            'user' => $this->uptd->findOrFail(decrypt($id)),
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pengguna UPTD',
            'breadcrumb_active' => 'Edit Data Pengguna UPTD',
            'title' => 'Edit Data Pengguna UPTD',
            'kecamatan' => $this->kecamatan::all(),
            'selected' => $this->uptd::pluck('kecamatan_id')->toArray(),
        ];
        return view('operator.pages.user.uptd.update', $data);
    }

    public function update(UpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $decryptedId = Crypt::decrypt($id);
            $uptd = $this->uptd->findOrFail($decryptedId);
            $user = $uptd->user;

            $uptd->update($request->all() + [
                'updated_at' => now(),
                'kecamatan_id' => $request->kecamatan,
            ]);

            $user->update($request->all() + [
                'updated_at' => now(),
            ]);

            DB::commit();
            return redirect('/operator/user/uptd')->with('success', 'Data Berhasil Diubah!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Data user gagal diubah! ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = $this->uptd->findOrFail($id);
            $user->user->delete();
            $user->delete();

            DB::commit();

            Alert::success('success', 'Data Berhasil Dihapus!');
            return back()->with('success', 'Data Berhasil Dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data Gagal Dihapus' . $e->getMessage());
            return back()->with('error', 'Data GagalDihapus!');
        }
    }
}
