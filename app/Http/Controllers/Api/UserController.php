<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penyuluh\Penyuluh;
use App\Models\Pertanian\Pertanian;
use App\Models\Pasar\PetugasPasar;
use App\Models\Pangan\Pangan;
use App\Models\Role;
use App\Models\Uptd\Uptd;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

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
            case Role::PANGAN:
                $detail = $user->pangan;
                break;
            case Role::PASAR:
                $detail = $user->pasar;
                break;
        }

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            'detail' => $detail,
            'pasar' => $detail->pasar->name ?? '',
            'kecamatan' => $detail->kecamatan ?? '',
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
                case Role::PANGAN:
                    Uptd::where('user_id', $user->id)->update($request->only(['alamat', 'no_telp']));
                    break;
                case Role::PASAR:
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

    public function searchEmail(Request $request)
    {
        $user = User::whereEmail($request->input('email'))->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email tidak ditemukan',
            ], 404);
        }
        $status = Password::sendResetLink($request->only('email'));
        if ($status == Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => 'success',
                'message' => 'Periksa Email Anda Untuk Mendapatkan Link Reset Password'
            ], 200);
        }
    }

    public function forgotPassword(Request $request, $id)
    {
        try {
            $request->validate([
                'new_password' => 'required|string|min:8|different:current_password',
                'confirm_password' => 'required|string|same:new_password',
            ]);

            $user = User::findOrFail($id);

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
                case Role::PANGAN:
                    $detail = $user->pangan;
                    break;
                case Role::PASAR:
                    $detail = $user->pasar;
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
}
