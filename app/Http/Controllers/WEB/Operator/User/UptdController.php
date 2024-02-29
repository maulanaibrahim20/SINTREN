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
use RealRashid\SweetAlert\Facades\Alert;

class UptdController extends Controller
{
    protected $user;

    protected $uptd;
    public function __construct(User $user, Uptd $uptd)
    {
        $this->user = $user;
        $this->uptd = $uptd;
    }
    public function index()
    {
        $data = [
            'title' => 'Data Pengguna UPTD',
        ];
        $users = $this->uptd::orderBy('created_at', 'asc')->get();
        return view('operator.pages.user.uptd.index', $data, compact('users'));
    }

    public function create()
    {
        return view('operator.pages.user.uptd.create');
    }
    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = $this->user->create($request->all() + [
                'username' => Str::slug($request->name),
                'password' => bcrypt('password'),
                'role_id' => Role::UPTD,
            ]);
            $this->uptd->create($request->all() + [
                'user_id' => $user->id,
            ]);
            $user->setAttribute('email_verified_at', Carbon::now());
            $user->setAttribute('remember_token', Str::random(10));
            $user->save();

            DB::commit();
            Alert::success('success', 'Data User Uptd Berhasil Ditambahkan!');
            return redirect('/operator/user/uptd')->with('success', 'Data User Uptd Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data User Uptd Gagal Ditambahkan!' . $e->getMessage());
            return back()->with('error', 'Data User Uptd gagal ditambahkan!');
        }
    }

    public function edit($id)
    {
        $user = $this->uptd->findOrFail($id);
        return view('operator.pages.user.uptd.update', compact('user'));
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $user = $this->uptd->findOrFail($id);
            $user->update($request->all() + [
                'updated_at' => now(),
            ]);
            $user->user->update($request->all() + [
                'updated_at' => now(),
            ]);
            DB::commit();
            Alert::success('success', 'Data Berhasil Diubah!');
            return redirect('/operator/user/uptd')->with('success', 'Data Berhasil Diubah!');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('error', 'Data user gagal diubah!' . $e->getMessage());
            return back()->with('error', 'Data user gagal diubah!');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
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
