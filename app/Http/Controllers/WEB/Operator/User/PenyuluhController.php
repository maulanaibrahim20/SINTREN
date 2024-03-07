<?php

namespace App\Http\Controllers\WEB\Operator\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\User\Penyuluh\CreateRequest;
use App\Http\Requests\Operator\User\Penyuluh\UpdateRequest;
use App\Models\Penyuluh\Penyuluh;
use App\Models\User;
use App\Models\Role;
use App\Models\Wilayah\Kecamatan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class PenyuluhController extends Controller
{
    protected $user;
    protected $penyuluh;
    protected $kecamatan;

    public function __construct(User $user, Penyuluh $penyuluh, Kecamatan $kecamatan)
    {
        $this->user = $user;
        $this->penyuluh = $penyuluh;
        $this->kecamatan = $kecamatan;
    }
    public function index()
    {
        $data = [
            'title' => 'Data Pengguna Penyuluh',
        ];
        $users = $this->penyuluh::orderBy('created_at', 'asc')->get();
        return view('operator.pages.user.penyuluh.index', compact('users'), $data);
    }

    public function create()
    {
        $kecamatan = $this->kecamatan::all();
        $selected = $this->penyuluh::pluck('kecamatan_id')->toArray(); // Mengonversi ke array agar dapat digunakan nanti
        return view('operator.pages.user.penyuluh.create', compact('kecamatan', 'selected'));
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
            ]);
            $user->setAttribute('email_verified_at', Carbon::now());
            $user->setAttribute('remember_token', Str::random(10));
            $user->save();
            DB::commit();
            Alert::success('success', 'User penyuluh berhasil ditambahkan!');
            return redirect('/operator/user/penyuluh')->with('success', 'User Penyuluh Berhasil Ditambahkan!');
        } catch (ValidationException $e) {
            DB::rollback();
            Alert::warning('kesalahan' . $e->errors());
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $er) {
            DB::rollback();
            Alert::error('error', 'User penyuluh gagal ditambahkan!' . $er->getMessage());
            return back()->with('error', 'Gagal Menambahkan User Penyuluh' . $er->getMessage());
        }
    }

    public function edit($id)
    {
        $user = $this->penyuluh->findOrFail($id);
        return view('operator.pages.user.penyuluh.update', compact('user'));
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $user = $this->penyuluh->findOrFail($id);
            $user->update($request->all() + [
                'updated_at' => Carbon::now(),
            ]);
            $user->user->update($request->all() + [
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
            Alert::success('success', 'User penyuluh berhasil diubah!');
            return redirect('/operator/user/penyuluh')->with('success', 'User Penyuluh Berhasil Diubah!');
        } catch (ValidationException $e) {
            DB::rollback();
            Alert::warning('kesalahan' . $e->errors());
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $er) {
            DB::rollback();
            Alert::error('error', 'User penyuluh gagal diubah!' . $er->getMessage());
            return back()->with('error', 'Gagal Mengubah User Penyuluh' . $er->getMessage());
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
        } catch (ValidationException $e) {
            DB::rollback();
            Alert::warning('kesalahan' . $e->errors());
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $er) {
            DB::rollback();
            Alert::error('error', 'User penyuluh gagal dihapus!' . $er->getMessage());
            return back()->with('error', 'Gagal Menghapus User Penyuluh' . $er->getMessage());
        }
    }
}
