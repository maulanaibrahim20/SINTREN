<?php

namespace App\Http\Requests\Penyuluh;

use Illuminate\Foundation\Http\FormRequest;

class LaporanPadiRequest extends FormRequest
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
            'desa' => ['required', 'string', 'max:255'],
            'jenis_padi' => ['required', 'string', 'max:255'],
            'jenis_bantuan' => ['required', 'string', 'max:255'],
            'tanaman_akhir_bulan_lalu' => ['required', 'numeric'],
            'panen' => ['required', 'numeric'],
            'tanam' => ['required', 'numeric'],
            'rusak' => ['required', 'numeric'],
            'tanam_akhir_bulan_laporan' => ['required', 'numeric'],
            'pengairan' => ['required', 'string', 'max:255'],
            'tanaman_akhir_bulan_lalu_pengairan' => ['required', 'numeric'],
            'panen_pengairan' => ['required', 'numeric'],
            'tanam_pengairan' => ['required', 'numeric'],
            'rusak_pengairan' => ['required', 'numeric'],
            'tanam_akhir_bulan_laporan_pengairan' => ['required', 'numeric'],
        ];
    }
}
