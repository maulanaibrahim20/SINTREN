<?php

namespace App\Http\Requests\Operator\User\Uptd;

use Illuminate\Foundation\Http\FormRequest;

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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_telp' => ['required', 'string', 'min:9', 'max:255'],
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
            'no_telp.string' => 'Nomor telepon harus berupa teks.',
            'no_telp.max' => 'Nomor telepon maksimal 255 karakter.',
            'no_telp.min' => 'Nomor telepon minimal 9 karakter.',
            'kecamatan.required' => 'Kecamatan harus dipilih.',
            'kecamatan.integer' => 'Kecamatan harus berupa angka.',
            'kecamatan.exists' => 'Kecamatan tidak ditemukan dalam database.',
            'kecamatan.unique' => 'Kecamatan sudah dipilih oleh pengguna lain.'
        ];
    }
}
