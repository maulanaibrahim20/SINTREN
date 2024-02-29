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
        ];
        $users = $this->pertanian::orderBy('created_at', 'asc')->get();
        return view('operator.pages.user.pertanian.index', compact('users'), $data);
    }

    public function create()
    {
        return view('operator.pages.user.pertanian.create');
    }

    public function store(CreateRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = $this->user->create($request->all() + [
                'username' => Str::slug($request->name),
                'password' => bcrypt('password'),
                'role_id' => Role::PERTANIAN,
            ]);
            $this->pertanian->create($request->all() + [
                'user_id' => $user->id,
            ]);
            $user->setAttribute('email_verified_at', Carbon::now());
            $user->setAttribute('remember_token', Str::random(10));
            $user->save();

            DB::commit();
            Alert::success('Success', 'Success Data Berhasil Ditambahkan');
            return redirect('/operator/user/pertanian')->with('success', 'Data User Pertanian Berhasil Ditambahkan');
        } catch (ValidationException $e) {
            DB::rollback();
            Alert::warning('kesalahan' . $e->errors());
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollback();
            $errorMessage = 'Gagal Menambahkan Data: ' . $e->getMessage();
            Alert::error('Error', $errorMessage);
            return back()->withInput()->withErrors($errorMessage);
        }
    }

    public function edit($id)
    {
        $user = $this->pertanian->findOrFail($id);
        return view('operator.pages.user.pertanian.update', compact('user'));
    }

    public function update(UpdatedRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $user = $this->pertanian->findOrFail($id);
            $user->update($request->all() + [
                'updated_at' => now(),
            ]);
            $user->user->update($request->all() + [
                'updated_at' => now(),
            ]);
            DB::commit();
            Alert::success('success', 'Data berhasil diubah1');
            return redirect('/operator/user/pertanian')->with('success', 'Success data berhasil diubah!');
        } catch (ValidationException $e) {
            DB::rollback();
            Alert::warning('kesalahan' . $e->errors());
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (\Exception $er) {
            DB::rollback();
            $errorMessage = 'Gagal Menambahkan Data: ' . $er->getMessage();
            Alert::error('Error', $errorMessage);
            return back()->withInput()->withErrors($errorMessage);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $user = $this->pertanian->findOrFail($id);
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
