<?php

namespace App\Imports;

use App\Models\Penyuluh\LaporanPadi;
use App\Models\Uptd\VerifyPadi;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportLaporanPadi implements ToModel, WithMultipleSheets, WithHeadingRow
{
    protected $sheetName;
    /**
     * Mengembalikan array sheet untuk diimpor.
     *
     * @return array
     */
    public function sheets(): array
    {
        return [
            'tahun2010' => $this,
            'tahun2011' => $this,
            'tahun2012' => $this,
            'tahun2013' => $this,
            'tahun2014' => $this,
            'tahun2015' => $this,
            'tahun2016' => $this,
            'tahun2017' => $this,
            'tahun2018' => $this,
            'tahun2019' => $this,
            'tahun2020' => $this,
            'tahun2021' => $this,
        ];
    }

    /**
     * Membuat model dari baris data.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $user = User::where('role_id', 4)->first();

        if (!isset($row['desaid']) || empty($row['desaid'])) {
            return null;
        }

        $dataTipe = [
            'tanam' => $row['tanam'],
            'panen' => $row['panen'],
            'puso/rusak' => $row['puso']
        ];

        foreach ($dataTipe as $tipeData => $nilai) {
            $bulan = rand(1, 12); // Bulan antara 1 sampai 12
            $tahun = $row['tahun']; // Tahun dari data excel

            $tanggal = rand(1, 30); // Tanggal acak antara 1 sampai 30

            // Penyesuaian tanggal berdasarkan bulan tertentu
            if ($bulan == 2) {
                $tanggal = rand(1, 28); // Februari maksimal 28 hari
            } elseif (in_array($bulan, [4, 6, 9, 11])) {
                $tanggal = rand(1, 30); // April, Juni, September, November maksimal 30 hari
            }

            $laporanPadi = new LaporanPadi([
                'user_id' => $user->id,
                'desa_id' => $row['desaid'],
                'kecamatan_id' => $row['kecamatanid'],
                'date' => sprintf('%04d-%02d-%02d', $tahun, $bulan, $tanggal), // Format tanggal YYYY-MM-DD
                'jenis_lahan' => rand(0, 1) == 1 ? 'sawah' : 'non sawah',
                'id_jenis_padi' => rand(1, 2),
                'jenis_bantuan' => rand(0, 1) == 1 ? 'bantuan pemerintah' : 'non bantuan pemerintah',
                'id_jenis_pengairan' => rand(1, 3),
                'tipe_data' => $tipeData,
                'nilai' => $nilai,
            ]);

            $laporanPadi->save();

            VerifyPadi::create([
                'laporan_id' => $laporanPadi->id,
                'user_id' => $laporanPadi->user_id,
                'status' => 'terima',
                'catatan' => null,
            ]);
        }

        return $laporanPadi;
    }
}
