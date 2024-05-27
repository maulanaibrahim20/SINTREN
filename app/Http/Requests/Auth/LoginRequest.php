<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'username' => 'required|string|min:3|max:25',
            "password" => "required|min:8|max:25",
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Kolom username wajib diisi.',
            'username.string' => 'Username harus berupa string.',
            'username.min' => 'Username minimal harus :min karakter.',
            'username.max' => 'Username tidak boleh lebih dari :max karakter.',
            'password.required' => 'Kolom password wajib diisi.',
            'password.string' => 'Password harus berupa string.',
            'password.min' => 'Password minimal harus :min karakter.',
            'password.max' => 'Password tidak boleh lebih dari :max karakter.',
        ];
    }
}
