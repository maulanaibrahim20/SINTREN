<?php

namespace App\Http\Controllers\WEB\Uptd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Uptd\EditProfile\UpdatePasswordUptdRequest;
use App\Http\Requests\Uptd\EditProfile\UpdateProfileUptdRequest;
use App\Models\Uptd\Uptd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EditProfileUptdController extends Controller
{
    protected $user, $uptd;

    public function __construct(User $user, Uptd $uptd)
    {
        $this->uptd = $uptd;
        $this->user = $user;
    }


    public function index()
    {
        $data['uptd'] = $this->uptd::where('user_id', Auth::user()->id)->first();
        return view('uptd.pages.editProfile.index', $data);
    }

    public function update(UpdateProfileUptdRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $uptd = $this->uptd::findOrFail($id);
            $uptd->update([
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,

            ]);
            $uptd->user->update([
                'name' => $request->nama,
                'email' => $request->email,
            ]);
            DB::commit();
            return back()->with('success', 'Data Berhasil Diubah!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function updatePassword(UpdatePasswordUptdRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $uptd = $this->uptd::findOrFail($id);
            $user = $uptd->user;

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
