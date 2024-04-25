<?php

namespace App\Http\Controllers\PANGAN;
use App\Models\Pasar\Pasar;
use App\Models\User;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\Operator\User\Pasar\CreateRequest;
use App\Http\Requests\Operator\User\Pasar\UpdateRequest;

class UserPasarController extends Controller
{
    protected $user;

    protected $pasar;
    public function __construct(User $user, Pasar $pasar)
    {
        $this->user = $user;
        $this->pasar = $pasar;
    }
    public function index()
    {
        $data = [
            'title' => 'Data Pengguna',
            'breadcrumb' => 'Dashboard',
            'breadcrumb_active' => 'Data Pengguna Pasar',
            'button_create' => 'Tambah Data Pengguna',
            'users' => $this->pasar::orderBy('created_at', 'asc')->get(),
        ];

        return view('pangan.views.user.pasar.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pangan.views.user.pasar.create');
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
            $this->pasar->create($request->all() + [
                'user_id' => $user->id,
            ]);
            $user->setAttribute('email_verified_at', Carbon::now());
            $user->setAttribute('remember_token', Str::random(10));
            $user->save();

            DB::commit();
            Alert::success('Success', 'Success Data Berhasil Ditambahkan');
            return redirect('/dinas_pangan/user/pasar')->with('success', 'Data User Pertanian Berhasil Ditambahkan');
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
