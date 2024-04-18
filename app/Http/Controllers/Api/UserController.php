<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $role = Role::where('id', $user->role_id)->first();
            if ($role->name == "PENYULUH") {
                $responseData = [
                    'status' => 'success',
                    'message' => 'Login successful',
                    'data' => $user
                ];
                return response()->json($responseData, 200);
            } else {
                $responseData = [
                    'status' => 'error',
                    'message' => 'Unauthorized',
                    'data' => null
                ];
                return response()->json($responseData, 401);
            }
        } else {
            $responseData = [
                'status' => 'error',
                'message' => 'Unauthorized',
                'data' => null
            ];
            return response()->json($responseData, 401);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'username' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $id,
                'role_id' => 'required|exists:roles,id',
            ]);

            $user = User::findOrFail($id);
            $user->update($request->only(['name', 'username', 'email', 'role_id']));
            $responseData = [
                'status' => 'success',
                'message' => 'Update successful.',
                'data' => null
            ];
            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Failed to update.',
                'data' => null
            ];
            return response()->json($responseData, 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|different:current_password',
                'confirm_password' => 'required|string|same:new_password',
            ]);

            $user = auth()->user();

            // Verifikasi password saat ini
            if (!Hash::check($request->current_password, $user->password)) {
                $responseData = [
                    'status' => 'error',
                    'message' => 'Current password is incorrect.',
                    'data' => null
                ];
                return response()->json($responseData, 400);
            }

            // Update password baru
            $users = User::findOrFail($request->id);
            $users->update([
                'password' => Hash::make($request->new_password),
            ]);
            $responseData = [
                'status' => 'success',
                'message' => 'Password changed successfully.',
                'data' => null
            ];
            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            $responseData = [
                'status' => 'error',
                'message' => 'Failed to change password.',
                'data' => null
            ];
            return response()->json($responseData, 500);
        }
    }
}
