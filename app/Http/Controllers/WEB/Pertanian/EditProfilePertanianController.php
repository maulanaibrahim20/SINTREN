<?php

namespace App\Http\Controllers\WEB\Pertanian;

use App\Http\Controllers\Controller;
use App\Models\Pertanian\Pertanian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EditProfilePertanianController extends Controller
{
    protected $user, $pertanian;

    public function __construct(User $user, Pertanian $pertanian)
    {
        $this->pertanian = $pertanian;
        $this->user = $user;
    }


    public function index()
    {
        $data['pertanian'] = $this->pertanian::where('user_id', Auth::user()->id)->first();
        return view('pertanian.pages.editProfile.index', $data);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $pertanian = $this->pertanian::findOrFail($id);
            $pertanian->update([
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,

            ]);
            $pertanian->user->update([
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

    public function updatePassword(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $pertanian = $this->pertanian::findOrFail($id);
            $user = $pertanian->user;

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
