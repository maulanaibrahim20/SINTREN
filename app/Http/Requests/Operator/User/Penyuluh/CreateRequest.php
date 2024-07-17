<?php

namespace App\Http\Requests\Operator\User\Penyuluh;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_telp' => ['required', 'numeric', 'min:9'],
            'kecamatan' => ['required', 'numeric', 'exists:kecamatans,id', Rule::unique('penyuluhs', 'kecamatan_id')],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama lengkap harus diisi.',
            'name.string' => 'Nama lengkap harus berupa teks.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.email' => 'Email harus berupa email yang valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'alamat.required' => 'Alamat harus diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',
            'alamat.max' => 'Alamat maksimal 255 karakter.',
            'no_telp.required' => 'Nomor telepon harus diisi.',
            'no_telp.numeric' => 'Nomor telepon harus berupa angka.',
            'no_telp.min' => 'Nomor telepon minimal 9 angka.',
            'kecamatan.required' => 'Kecamatan harus dipilih.',
            'kecamatan.numeric' => 'Kecamatan harus berupa angka.',
            'kecamatan.exists' => 'Kecamatan tidak ditemukan dalam database.',
            'kecamatan.unique' => 'Kecamatan sudah dipilih oleh pengguna lain.',
        ];
    }
}
