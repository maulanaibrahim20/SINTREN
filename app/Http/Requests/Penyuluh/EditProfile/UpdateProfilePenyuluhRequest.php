<?php

namespace App\Http\Requests\Penyuluh\EditProfile;

use App\Models\Penyuluh\Penyuluh;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfilePenyuluhRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('id'); // Ambil id dari rute
        $penyuluh = Penyuluh::findOrFail($id); // Ambil model Penyuluh dengan ID yang sesuai

        return [
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($penyuluh->user->id),
            ],
            'no_telp' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_telp.required' => 'Nomor telepon wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.'
        ];
    }
}
