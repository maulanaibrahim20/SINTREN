<?php

namespace App\Http\Controllers\PANGAN;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pangan\Pangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\File;

class EditProfilePanganController extends Controller
{
    protected $pangan, $user;

    public function __construct(Pangan $pangan, User $user)
    {
        $this->pangan = $pangan;
        $this->user = $user;
    }

    public function index()
    {
        $panganId = Auth::user()->id;
        $data['pangan'] = $this->pangan::where('user_id', $panganId)->first();
        return view('pangan.views.edit_profile.index', $data);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $pangan = $this->pangan::findOrFail($id);

            if ($request->hasFile('gambar')) {
                if ($pangan->gambar && strpos($pangan->gambar, 'images') !== false) {
                    $oldImagePath = storage_path('app/public/' . $pangan->gambar);
                    if (File::exists($oldImagePath)) {
                        File::delete($oldImagePath);
                    }
                }

                $image = $request->file('gambar');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = storage_path('app/public/images');
                $image->move($destinationPath, $name);
                $data['gambar'] = 'images/' . $name;
            } else {
                $data['gambar'] = $pangan->gambar;
            }

            $pangan->update([
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'gambar' => $data['gambar'],
            ]);

            $pangan->user->update([
                'name' => $request->nama,
                'email' => $request->email,
            ]);

            DB::commit();
            return redirect()->route('editProfile')->with('success', 'Data Berhasil Diubah');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }


    public function updatePassword(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $pangan = $this->pangan::findOrFail($id);
            $user = $pangan->user;

            if (!Hash::check($request->password_lama, $user->password)) {
                Alert::error('Error', 'Password lama tidak sesuai.');
                return back()->with('error', 'Password lama tidak sesuai.');
            }

            if ($request->password_baru !== $request->konfirmasi_password) {
                Alert::error('Error', 'Konfirmasi password tidak sesuai dengan password baru.');
                return back()->with('error', 'Konfirmasi password tidak sesuai dengan password baru.');
            }

            $user->update([
                'password' => bcrypt($request->password_baru),
            ]);

            DB::commit();
            Alert::success('Success', 'Password berhasil diubah.');
            return back()->with('success', 'Password berhasil diubah');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }
}
