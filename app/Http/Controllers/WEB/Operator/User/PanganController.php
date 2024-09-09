<?php

namespace App\Http\Controllers\WEB\Operator\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\User\Pangan\CreateRequest;
use App\Http\Requests\Operator\User\Pangan\UpdatedRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Pangan\Pangan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class PanganController extends Controller
{

    protected $user;
    protected $pangan;

    public function __construct(User $user, Pangan $pangan)
    {
        $this->user = $user;
        $this->pangan = $pangan;
    }
    public function index()
    {
        $data = [
            'title' => 'Data Pengguna Pangan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Data Pengguna Pangan',
            'add_button' => 'Tambah Data Pengguna',
            'users' => $this->pangan::orderBy('created_at', 'asc')->get(),
        ];
        return view('operator.pages.user.pangan.index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Buat Akun Pengguna Pangan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pengguna Pangan',
            'breadcrumb_active' => 'Tambah Pengguna Pangan',
        ];
        return view('operator.pages.user.pangan.create', $data);
    }

    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = $this->user->create($request->all() + [
                'username' => Str::slug($request->name),
                'password' => bcrypt('password'),
                'role_id' => Role::PANGAN,
            ]);
            $this->pangan->create($request->all() + [
                'user_id' => $user->id,
            ]);

            DB::commit();
            Alert::success('Success', 'Pengguna Pangan Berhasil Ditambahkan');

            return redirect('/operator/user/pangan')->with('success', 'Pengguna Pangan Berhasil Ditambahkan!');
        } catch (\Exception $er) {
            DB::rollback();
            return back()->with('error', 'Gagal Menambahkan Pengguna Pangan' . $er->getMessage());
        }
    }


    // public function store(CreateRequest $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         // Simpan data pengguna
    //         $user = $this->user->create($request->all() + [
    //             'username' => Str::slug($request->name),
    //             'password' => bcrypt('password'),
    //             'role_id' => Role::PANGAN,
    //         ]);

    //         // Proses upload gambar
    //         $gambarPath = 'images/profile.png'; // Default image path

    //         if ($request->hasFile('gambar')) {
    //             $image = $request->file('gambar');
    //             $name = time() . '.' . $image->getClientOriginalExtension();
    //             $destinationPath = storage_path('app/public/images'); // Simpan di direktori storage/app/public/images
    //             $image->move($destinationPath, $name);
    //             $gambarPath = 'images/' . $name; // Path yang disimpan di database, perhatikan 'images/' sebagai prefix
    //         }

    //         // Simpan data pangan dengan path gambar
    //         $this->pangan->create($request->all() + [
    //             'user_id' => $user->id,
    //             'gambar' => $gambarPath,
    //         ]);

    //         DB::commit();
    //         Alert::success('Success', 'Pengguna Pangan Berhasil Ditambahkan');

    //         return redirect('/operator/user/pangan')->with('success', 'Pengguna Pangan Berhasil Ditambahkan!');
    //     } catch (\Exception $er) {
    //         DB::rollback();
    //         return back()->with('error', 'Gagal Menambahkan Pengguna Pangan: ' . $er->getMessage());
    //     }
    // }


    public function show($id)
    {
        $data = [
            'title' => 'Detail Data Pengguna Pangan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pengguna Pangan',
            'breadcrumb_active' => 'Detail Data Pengguna Pangan',
            'user'  => $this->pangan->findOrFail(decrypt($id)),
        ];
        return view('operator.pages.user.pangan.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = [
            'title' => 'Buat Akun Pengguna Pangan',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pengguna Pangan',
            'breadcrumb_active' => 'Edit Pengguna Pangan',
            'user' => $this->pangan->findOrFail(decrypt($id)),
        ];
        return view('operator.pages.user.pangan.update', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $user = $this->pangan->findOrFail(decrypt($id));
            $user->update($request->all() + [
                'updated_at' => now(),
            ]);
            $user->user->update($request->all() + [
                'updated_at' => now(),
            ]);
            DB::commit();
            Alert::success('success', 'Data berhasil diubah!');
            return redirect('/operator/user/pangan')->with('success', 'Success data berhasil diubah!');
        } catch (\Exception $er) {
            DB::rollback();
            $errorMessage = 'Gagal Menambahkan Data: ' . $er->getMessage();
            Alert::error('Error', $errorMessage);
            return back()->withInput()->withErrors($errorMessage);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $user = $this->pangan->findOrFail($id);
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
