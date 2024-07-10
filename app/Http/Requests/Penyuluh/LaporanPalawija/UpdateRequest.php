<?php

namespace App\Http\Requests\Penyuluh\LaporanPalawija;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'date' => 'required|date',
            'desa' => 'required',
            'jenis_lahan' => 'required',
            'jenis_bantuan' => 'required',
            'jenis_palawija' => 'required',
            'jenis_data' => 'required',
            'nilai' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'date.required' => 'Tanggal wajib diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'desa.required' => 'Desa penugasan wajib dipilih.',
            'jenis_lahan.required' => 'Jenis lahan wajib dipilih.',
            'jenis_bantuan.required' => 'Jenis bantuan wajib dipilih.',
            'jenis_palawija.required' => 'Jenis palawija wajib dipilih.',
            'jenis_data.required' => 'Jenis data wajib dipilih.',
            'nilai.required' => 'Nilai wajib diisi.',
            'nilai.numeric' => 'Nilai harus berupa angka.'
        ];
    }
}
