<?php

namespace App\Http\Requests\Uptd\EditProfile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordUptdRequest extends FormRequest
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
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|different:password_lama',
            'konfirmasi_password' => 'required|same:password_baru',
        ];
    }

    public function messages()
    {
        return [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min' => 'Password baru minimal harus 6 karakter.',
            'password_baru.different' => 'Password baru harus berbeda dengan password lama.',
            'konfirmasi_password.required' => 'Konfirmasi password wajib diisi.',
            'konfirmasi_password.same' => 'Konfirmasi password harus sama dengan password baru.',
        ];
    }
}
