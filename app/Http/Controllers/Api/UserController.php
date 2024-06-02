<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\Penyuluh;
use App\Models\Pertanian\Pertanian;
use App\Models\Role;
use App\Models\Uptd\Uptd;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
                'data' => null
            ], 401);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
                'data' => null
            ], 401);
        }

        $role = Role::find($user->role_id);

        $detail = null;
        switch ($user->role_id) {
            case Role::PERTANIAN:
                $detail = $user->pertanian;
                break;
            case Role::UPTD:
                $detail = $user->uptd;
                break;
            case Role::PENYULUH:
                $detail = $user->penyuluh;
                break;
        }

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            'detail' => $detail,
            'kecamatan' => $detail->kecamatan,
            'role_name' => $role ? $role->name : 'No Role'
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => $userData
        ], 200);
    }


    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'username' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $id,
                'alamat' => 'required|string',
                'no_telp' => 'required'
            ]);

            $user = User::findOrFail($id);

            $user->update($request->only(['name', 'username', 'email']));

            switch ($user->role_id) {
                case Role::PERTANIAN:
                    Pertanian::where('user_id', $user->id)->update($request->only(['alamat', 'no_telp']));
                    break;
                case Role::PENYULUH:
                    Penyuluh::where('user_id', $user->id)->update($request->only(['alamat', 'no_telp']));
                    break;
                case Role::UPTD:
                    Uptd::where('user_id', $user->id)->update($request->only(['alamat', 'no_telp']));
                    break;
            }

            $responseData = [
                'status' => 'success',
                'message' => 'Update berhasil.',
                'data' => null
            ];
            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Update gagal',
                'data' => null
            ];
            return response()->json($responseData, 500);
        }
    }

    public function changePassword(Request $request, $id)
    {
        try {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|different:current_password',
                'confirm_password' => 'required|string|same:new_password',
            ]);

            $user = User::findOrFail($id);

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password lama tidak ditemukan/salah.',
                    'data' => null
                ], 400);
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Password berhasil diubah.',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password gagal diubah: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function getUserById($id)
    {
        try {
            $user = User::findOrFail($id);
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'data' => null
                ], 401);
            }

            $role = Role::find($user->role_id);

            $detail = null;
            switch ($user->role_id) {
                case Role::PERTANIAN:
                    $detail = $user->pertanian;
                    break;
                case Role::UPTD:
                    $detail = $user->uptd;
                    break;
                case Role::PENYULUH:
                    $detail = $user->penyuluh;
                    break;
            }

            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'detail' => $detail,
                'role_name' => $role ? $role->name : 'No Role'
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil didapatkan',
                'data' => $userData
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan.',
                'data' => null
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mendapatkan data: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function getVerification()
    {
        $verify = Verification::all();
        $responseData = [
            'status' => 'success',
            'message' => 'Get data successful',
            'data' => $verify
        ];
        return response()->json($responseData);
    }

    public function storeVerify(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|string',
            'kecamatan_id' => 'required|string',
            'desa_id' => 'required|string',
            'date' => 'required|string',
            'isVerify' => 'required|bool',
        ]);

        try {
            $padi = new Verification();
            $padi->fill($validated);
            $padi->save();

            $responseData = [
                'status' => 'success',
                'message' => 'Berhasil menyimpan data',
                'data' => $padi,
            ];
            return response()->json($responseData, 201);
        } catch (QueryException $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Gagal menyimpan data. Database error: ' . $e->getMessage(),
                'data' => null
            ];
            return response()->json($responseData, 500);
        } catch (\Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Gagal menyimpan data. ' . $e->getMessage(),
                'data' => null
            ];
            return response()->json($responseData, 500);
        }
    }

    public function updateVerify(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|string',
            'kecamatan_id' => 'required|string',
            'desa_id' => 'required|string',
            'date' => 'required|string',
            'isVerify' => 'required|bool',
        ]);

        try {
            $padi = Verification::findOrFail($id);
            $padi->fill($validated);
            $padi->save();

            $responseData = [
                'status' => 'success',
                'message' => 'Berhasil mengupdate data',
                'data' => $padi,
            ];
            return response()->json($responseData, 200);
        } catch (QueryException $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Gagal mengupdate data. Database error: ' . $e->getMessage(),
                'data' => null
            ];
            return response()->json($responseData, 500);
        } catch (\Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Gagal mengupdate data. ' . $e->getMessage(),
                'data' => null
            ];
            return response()->json($responseData, 500);
        }
    }
}
