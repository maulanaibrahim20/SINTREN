<?php

namespace App\Http\Controllers\WEB\Uptd\Akun_Penyuluh;

use App\Http\Controllers\Controller;
use App\Http\Requests\Uptd\StorePenyuluhRequest;
use App\Http\Requests\Uptd\UpdatePenyuluhRequest;
use App\Models\Penyuluh\LuasLahanWilayah;
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
    protected $user, $penyuluh, $desa, $penugasan, $luasLahanWilayah;

    public function __construct()
    {
        $this->user = new User();
        $this->penyuluh = new Penyuluh();
        $this->desa = new Desa();
        $this->penugasan = new PenugasanPenyuluh();
        $this->luasLahanWilayah = new LuasLahanWilayah();
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
        $penugasan = $this->penugasan::whereIn('user_id', $userIds)->get();

        $penyuluh->each(function ($p) use ($penugasan) {
            $p->penugasan = $penugasan->where('user_id', $p->user_id);
        });

        $data = [
            'penyuluh' => $penyuluh,
            'desa' => $this->luasLahanWilayah::where('kecamatan_id', Auth::user()->uptd->kecamatan->id)->get(),
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
    public function store(StorePenyuluhRequest $request)
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
                'alamat' => $request['alamat'],
                'no_telp' => $request['no_telp'],
                'createdBy' => Auth::user()->id,
            ]);
            DB::commit();

            return redirect('/uptd/pengguna/penyuluhUptd')->with('success', 'Success data penyuluh berhasil dibuat!');
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
        $content = [
            'title' => 'Detail Penyuluh',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Akun Penyuluh',
            'breadcrumb_active' => 'Detail Penyuluh',
        ];
        $data['show'] = $this->penyuluh::findOrFail(decrypt($id));
        $data['penugasan'] = $this->penugasan::where('user_id', $data['show']->user_id)->get();
        return view('uptd.pages.user.penyuluh.show', $data, $content);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $content = [
            'title_1' => 'Edit Desa Penugasan Penyuluh',
            'title' => 'Edit Akun Penyuluh',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_1' => 'Akun Penyuluh',
            'breadcrumb_active' => 'Edit Akun Penyuluh',
        ];
        $data['edit'] = $this->penyuluh::findOrFail(decrypt($id));
        $data['penugasan'] = $this->penugasan::where('user_id', $data['edit']->user_id)->get();
        $data['desa'] = $this->luasLahanWilayah::where('kecamatan_id', Auth::user()->uptd->kecamatan->id)->get();
        $data['assigned_desa_ids'] = $data['penugasan']->pluck('desa_id')->toArray();
        return view('uptd.pages.user.penyuluh.update', $data, $content);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_telp' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
        ]);
        try {
            DB::beginTransaction();

            $penyuluh = $this->penyuluh::findOrFail($id);
            $penyuluh->update([
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
            ]);

            $existingUser = User::where('email', $request->email)
                ->where('id', '<>', $penyuluh->user->id)
                ->where('role_id', Role::PENYULUH)
                ->first();

            if ($existingUser) {
                DB::rollback();
                return back()->with('error', 'Email sudah digunakan.');
            }

            $penyuluh->user->update([
                'name' => $request->name,
                'username' => Str::slug($request->name),
                'email' => $request->email,
            ]);

            DB::commit();

            return redirect('/uptd/pengguna/penyuluhUptd')->with('success', 'Data penyuluh berhasil diubah!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Data penyuluh gagal diubah!' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $penyuluh = $this->penyuluh::findOrFail($id);
            $penyuluh->delete();
            $penyuluh->user->delete();
            DB::commit();
            return redirect('/uptd/pengguna/penyuluhUptd')->with('success', 'Data penyuluh berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Data penyuluh gagal dihapus!' . $e->getMessage());
        }
    }

    public function penugasan(Request $request)
    {
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

    public function updatePenugasan(Request $request, $user_id)
    {
        try {
            DB::beginTransaction();

            // Menghapus penugasan lama
            $this->penugasan::where('user_id', $user_id)->delete();

            // Menambahkan penugasan baru
            $array_desa = $request->penugasan;
            foreach ($array_desa as $desa_id) {
                $this->penugasan->create([
                    'desa_id' => $desa_id,
                    'user_id' => $user_id, // Menggunakan $user_id yang diterima dari URL
                ]);
            }

            DB::commit();
            return redirect('/uptd/pengguna/penyuluhUptd')->with('success', 'Penugasan Untuk Penyuluh Berhasil Diubah!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: Terjadi Kesalahan - ' . $e->getMessage());
        }
    }
}
