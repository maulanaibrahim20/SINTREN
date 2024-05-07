<?php

namespace App\Http\Controllers\WEB\Uptd\User;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\Penyuluh;
use App\Models\User;
use App\Models\Wilayah\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UptdPenyuluhController extends Controller
{
    protected $user, $penyuluh, $desa;

    public function __construct()
    {
        $this->user = new User();
        $this->penyuluh = new Penyuluh();
        $this->desa = new Desa();
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
        $data = [
            'penyuluh' => $this->penyuluh::where('kecamatan_id', Auth::user()->uptd->kecamatan->id)->get(),

            'selected' => $this->penyuluh->pluck('desa_id')->first(),
        ];
        return view('uptd.pages.user.penyuluh.index', $content, $data);
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
        $data = [
            'desa' => $this->desa::where('district_id', Auth::user()->uptd->kecamatan->id)->orderBy('name', 'ASC')->get(),
        ];
        return view('uptd.pages.user.penyuluh.create', $content, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
        try {
            DB::beginTransaction();
            $user = $this->user->create($request->all() + [
                'username' => Str::slug($request->name),
                'password' => bcrypt('password'),
                'role_id' => Role::PENYULUH,
            ]);
            $this->penyuluh->create($request->all() + [
                'user_id' => $user['id'],
                'kecamatan_id' => $request->kecamatan,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
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
}
