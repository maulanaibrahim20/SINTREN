<?php

namespace App\Http\Requests\Pangan\SubjenisPangan;

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
            'jenis_pangan_id' => 'required|exists:jenis_pangans,id',
            'name' => 'required|string|max:255',
        ];
    }
}
