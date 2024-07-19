<?php

namespace App\Http\Controllers\PANGAN;
use App\Models\Pasar\PetugasPasar;
use App\Models\Pasar\Pasar;
use App\Models\User;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Operator\User\Pasar\CreateRequest;
use App\Http\Requests\Operator\User\Pasar\UpdateRequest;

class UserPasarController extends Controller
{
    protected $user;
    protected $petugaspasar;
    protected $pasar;

    public function __construct(User $user, PetugasPasar $petugaspasar, Pasar $pasar)
    {
        $this->user = $user;
        $this->petugaspasar = $petugaspasar;
        $this->pasar = $pasar;
    }
    public function index()
    {
        $data = [
            'title' => 'Data Pengguna',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Data Pengguna Pasar',
            'add_button' => 'Tambah Data Pengguna',
            'users' => $this->petugaspasar::orderBy('created_at', 'asc')->get(),
        ];

        return view('pangan.views.user.pasar.index', $data);
    }

    /**
    * Show the form for creating a new resource.
    */
    public function create()
    {
        $data = [
            'title' => 'Tambah Data Pengguna Pasar',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pengguna Pasar',
            'breadcrumb_active' => 'Tambah Data Pengguna Pasar',
            'pasar' => $this->pasar::all(),
            'selected' => $this->petugaspasar::pluck('pasar_id')->toArray(),
        ];

        return view('pangan.views.user.pasar.create', $data);
    }

    /**
    * Store a newly created resource in storage.
    */

    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = $this->user->create($request->all() + [
                'username' => Str::slug($request->name),
                'password' => bcrypt('password'),
                'role_id' => Role::PASAR,
            ]);
            $this->petugaspasar->create($request->all() + [
                'user_id' => $user->id,
                'pasar_id' => $request->pasar,
                'gambar' => $request->gambar ?? 'image_pangan/profile.png',
            ]);

            DB::commit();
            Alert::success('Success', 'Pengguna Pasar Berhasil Ditambahkan');

            return redirect('/pangan/user/pasar')->with('success', 'User Pasar Berhasil Ditambahkan!');
        } catch (\Exception $er) {
            DB::rollback();
            return back()->with('error', 'Gagal Menambahkan User Pasar' . $er->getMessage());
        }
    }

    /**
    * Display the specified resource.
    */
    public function show($id)
    {
        $data = [
            'user'  => $this->petugaspasar->findOrFail(decrypt($id)),
            'title' => 'Detail Data Pengguna Pasar',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pengguna Pasar',
            'breadcrumb_active' => 'Detail Data Pengguna Pasar',
        ];
        return view('pangan.views.user.pasar.show', $data);
    }

    /**
    * Show the form for editing the specified resource.
    */
    public function edit($id)
    {
        $user = $this->petugaspasar->findOrFail(decrypt($id));
        $pasar = $this->pasar::all();
        $data = [
            'pasar' => $this->pasar::where('id', $user->pasar_id)->get(),
            'selected_pas' => $user->pasar_id,
            'title' => 'Edit Data Pengguna Pasar',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Data Pengguna Pasar',
            'breadcrumb_active' => 'Edit Data Pengguna Pasar',
        ];
        return view('pangan.views.user.pasar.update', $data, compact('user', 'pasar'));
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(UpdatedRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $user = $this->petugaspasar->findOrFail($id);
            $user->update($request->all() + [
                'updated_at' => now(),
                'pasar_id' => $request->pasar
            ]);
            $user->user->update($request->all() + [
                'updated_at' => now(),
            ]);
            DB::commit();
            Alert::success('success', 'Pengguna Pasar Berhasil Diubah!');
            return redirect('/pangan/user/pasar')->with('success', 'Pengguna Pasar Berhasil Diubah!');
        } catch (ValidationException $e) {
            DB::rollback();
            Alert::warning('kesalahan' . $e->errors());
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $er) {
            DB::rollback();
            Alert::error('error', 'Pengguna Pasar Gagal Diubah!' . $er->getMessage());
            return back()->with('error', 'Gagal Mengubah Pengguna Pasar' . $er->getMessage());
        }
    }

    /**
    * Remove the specified resource from storage.
    */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $user = $this->petugaspasar->findOrFail($id);
            $user->user->delete();
            $user->delete();

            DB::commit();

            Alert::success('success', 'Pengguna Pasar Berhasil Dihapus!');
            return back()->with('success', 'Pengguna Pasar Berhasil Dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Pengguna Pasar Berhasil Gagal Dihapus' . $e->getMessage());
            return back()->with('error', 'Pengguna Pasar Berhasil Dihapus!');
        }
    }
}
