<?php

namespace App\Http\Controllers\WEB\Penyuluh;

use App\Http\Controllers\Controller;
use App\Http\Requests\Penyuluh\EditProfile\UpdatePasswordPenyuluhRequest;
use App\Http\Requests\Penyuluh\EditProfile\UpdateProfilePenyuluhRequest;
use App\Models\Penyuluh\Penyuluh;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class EditProfileController extends Controller
{
    protected $penyuluh, $user;
    public function __construct(Penyuluh $penyuluh, User $user)
    {
        $this->penyuluh = $penyuluh;
        $this->user = $user;
    }
    public function index()
    {
        $penyuluhId = Auth::user()->id;
        $data['penyuluh'] = $this->penyuluh::where('user_id', $penyuluhId)->first();
        return view('penyuluh.pages.editProfile.index', $data);
    }

    public function update(UpdateProfilePenyuluhRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $penyuluh = $this->penyuluh::findOrFail($id);
            $penyuluh->update([
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
            ]);
            $penyuluh->user->update([
                'name' => $request->nama,
                'email' => $request->email,
            ]);
            DB::commit();
            return back()->with('success', 'Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function updatePassword(UpdatePasswordPenyuluhRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $penyuluh = $this->penyuluh::findOrFail($id);
            $user = $penyuluh->user;

            if (!Hash::check($request->password_lama, $user->password)) {
                return back()->with('error', 'Password lama tidak sesuai.');
            }

            if ($request->password_baru !== $request->konfirmasi_password) {
                return back()->with('error', 'Konfirmasi password tidak sesuai dengan password baru.');
            }

            $user->update([
                'password' => bcrypt($request->password_baru),
            ]);

            DB::commit();
            return back()->with('success', 'Password berhasil diubah');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }
}
