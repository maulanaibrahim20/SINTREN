<?php

namespace App\Http\Controllers\WEB\Uptd\Akun_Penyuluh;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\Penyuluh;
use App\Models\Role;
use App\Models\User;
use App\Models\Uptd\PenugasanPenyuluh;
use App\Models\Wilayah\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UptdAkunPenyuluhController extends Controller
{
    protected $user, $penyuluh, $desa, $penugasan;

    public function __construct()
    {
        $this->user = new User();
        $this->penyuluh = new Penyuluh();
        $this->desa = new Desa();
        $this->penugasan = new PenugasanPenyuluh();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $content = [
            'title' => 'Data Penyuluh',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Akun Penyuluh',
            'button_create' => 'Tambah Penyuluh',
            'title_create' => 'Tambah Penyuluh',
        ];

        $penyuluh = $this->penyuluh::where('kecamatan_id', Auth::user()->uptd->kecamatan->id)->get();
        $userIds = $penyuluh->pluck('user_id')->toArray();
        $data = [
            'penyuluh' => $penyuluh,
            'desa' => $this->desa::where('district_id', Auth::user()->uptd->kecamatan->id)->orderBy('name', 'ASC')->get(),
            'penugasan' => $this->penugasan::whereIn('user_id', $userIds)->get(),
        ];

        return view('uptd.pages.user.penyuluh.index', array_merge($content, $data));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $content = [
            'title' => 'Buat Akun Penyuluh',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Akun Penyuluh',
            'breadcrumb_active' => 'Buat Akun Penyuluh',
        ];
        $kecamatanId = Auth::user()->uptd->kecamatan->id;
        $data = [
            'desa' => $this->desa::where('district_id', $kecamatanId)->orderBy('name', 'ASC')->get(),
        ];
        return view('uptd.pages.user.penyuluh.create', $content, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $kecamatanId = Auth::user()->uptd->kecamatan->id;
        try {
            DB::beginTransaction();
            $user = $this->user->create([
                'name' => $request['name'],
                'username' => Str::slug($request['name']),
                'email' => $request['email'],
                'password' => bcrypt('password'),
                'role_id' => Role::PENYULUH,
            ]);
            $this->penyuluh->create([
                'user_id' => $user['id'],
                'kecamatan_id' => $kecamatanId,
                'desa_id' => $request['desa'],
                'alamat' => $request['alamat'],
                'no_telp' => $request['no_telp'],
                'createdBy' => Auth::user()->id,
            ]);
            DB::commit();

            return redirect('/uptd/pengguna/penyuluh')->with('success', 'Success data penyuluh berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Pengguna penyuluh gagal ditambahkan!' . $e->getMessage());
        }
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function penugasan(Request $request)
    {
        dd($request->all());
        try {
            DB::beginTransaction();

            $array_desa = $request->desa;

            foreach ($array_desa as $desa_id) {
                $this->penugasan->create([
                    'desa_id' => $desa_id,
                    'user_id' => $request->name,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Penugasaan Untuk Penyuluh Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error Terjadi Kesalahan' . $e->getMessage());
        }
    }
}
