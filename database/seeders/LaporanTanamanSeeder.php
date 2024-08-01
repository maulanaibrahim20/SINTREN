<?php

namespace Database\Seeders;

use App\Models\Penyuluh\LaporanPadi;
use App\Models\Penyuluh\LaporanPalawija;
use App\Models\Uptd\VerifyPadi;
use App\Models\Uptd\VerifyPalawija;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class LaporanTanamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $desaKecamatan = [
            '3212160007' => '3212160',
            '3212160008' => '3212160',
            '3212160009' => '3212160',
            '3212160010' => '3212160',
            '3212160011' => '3212160',
            '3212160012' => '3212160',
            '3212160013' => '3212160',
            '3212160014' => '3212160',
            '3212160015' => '3212160',
            '3212160016' => '3212160',
            '3212161001' => '3212161',
            '3212161002' => '3212161',
            '3212161003' => '3212161',
            '3212161004' => '3212161',
            '3212161005' => '3212161',
            '3212161006' => '3212161',
            '3212161007' => '3212161',
            '3212162001' => '3212162',
            '3212162002' => '3212162',
            '3212162003' => '3212162',
            '3212162004' => '3212162',
            '3212162005' => '3212162',
            '3212162006' => '3212162',
            '3212170001' => '3212170',
            '3212170002' => '3212170',
            '3212170003' => '3212170',
            '3212170004' => '3212170',
            '3212170005' => '3212170',
            '3212170006' => '3212170',
            '3212170007' => '3212170',
            '3212170008' => '3212170',
            '3212170009' => '3212170',
            '3212170010' => '3212170',
            '3212170011' => '3212170',
            '3212170015' => '3212170',
            '3212171001' => '3212171',
            '3212171002' => '3212171',
            '3212171003' => '3212171',
            '3212171004' => '3212171',
            '3212171005' => '3212171',
            '3212171006' => '3212171',
            '3212171007' => '3212171',
            '3212171008' => '3212171',
            '3212180001' => '3212180',
            '3212180002' => '3212180',
            '3212180003' => '3212180',
            '3212180004' => '3212180',
            '3212180005' => '3212180',
            '3212180006' => '3212180',
            '3212180008' => '3212180',
            '3212180010' => '3212180',
            '3212180012' => '3212180',
            '3212180013' => '3212180',
            '3212190002' => '3212190',
            '3212190003' => '3212190',
            '3212190004' => '3212190',
            '3212190005' => '3212190',
            '3212190006' => '3212190',
            '3212190007' => '3212190',
            '3212190008' => '3212190',
            '3212190009' => '3212190',
            '3212190010' => '3212190',
            '3212190011' => '3212190',
            '3212190012' => '3212190',
            '3212190013' => '3212190',
            '3212200001' => '3212200',
            '3212200002' => '3212200',
            '3212200003' => '3212200',
            '3212200004' => '3212200',
            '3212200005' => '3212200',
            '3212200006' => '3212200',
            '3212200007' => '3212200',
            '3212200008' => '3212200',
            '3212210001' => '3212210',
            '3212210002' => '3212210',
            '3212210003' => '3212210',
            '3212210004' => '3212210',
            '3212210006' => '3212210',
            '3212210007' => '3212210',
            '3212210008' => '3212210',
            '3212210009' => '3212210',
            '3212210010' => '3212210',
            '3212210011' => '3212210',
            '3212210012' => '3212210',
            '3212210013' => '3212210',
            '3212220001' => '3212220',
            '3212220002' => '3212220',
            '3212220003' => '3212220',
            '3212220004' => '3212220',
            '3212220005' => '3212220',
            '3212220006' => '3212220',
            '3212220007' => '3212220',
            '3212220008' => '3212220',
            '3212221001' => '3212221',
            '3212221002' => '3212221',
            '3212221003' => '3212221',
            '3212221004' => '3212221',
            '3212221006' => '3212221',
            '3212221007' => '3212221',
            '3212221008' => '3212221',
        ];

        $startDate = Carbon::create(2010, 1, 1);
        $endDate = Carbon::create(2021, 12, 31);

        $currentDate = $startDate->copy();

        // DB::table("laporan_padis")->truncate();
        // DB::table("verify_padis")->truncate();
        DB::table("verify_palawijas")->truncate();
        DB::table("prediksis")->truncate();
        DB::table("prediksi_sps")->truncate();

        // while ($currentDate->lte($endDate)) {
        //     $desa_id = array_rand($desaKecamatan);
        //     $kecamatan_id = $desaKecamatan[$desa_id];

        //     $laporanPadi = LaporanPadi::create([
        //         'user_id' => Str::uuid(),
        //         'desa_id' => strval($desa_id),
        //         'kecamatan_id' => $kecamatan_id,
        //         'date' => $currentDate->toDateString(),
        //         'jenis_lahan' => rand(0, 1) == 1 ? 'sawah' : 'non sawah',
        //         'id_jenis_padi' => rand(1, 2),
        //         'jenis_bantuan' => rand(0, 1) == 1 ? 'bantuan pemerintah' : 'non bantuan pemerintah',
        //         'id_jenis_pengairan' => rand(1, 3),
        //         'tipe_data' => ['panen', 'tanam', 'puso/rusak'][rand(0, 2)],
        //         'nilai' => rand(100, 1000),
        //     ]);

        //     VerifyPadi::create([
        //         'laporan_id' => $laporanPadi->id,
        //         'user_id' => $laporanPadi->user_id,
        //         'status' => 'tunggu',
        //         'catatan' => null,
        //     ]);

        //     $currentDate->addDay();
        // }

        // $currentDate = $startDate->copy();

        DB::table("laporan_palawijas")->truncate();

        while ($currentDate->lte($endDate)) {
            $desa_id = array_rand($desaKecamatan);
            $kecamatan_id = $desaKecamatan[$desa_id];

            $laporanPalawija = LaporanPalawija::create([
                'user_id' => Str::uuid(),
                'desa_id' => strval($desa_id),
                'kecamatan_id' => $kecamatan_id,
                'date' => $currentDate->toDateString(),
                'jenis_lahan' => rand(0, 1) == 1 ? 'sawah' : 'non sawah',
                'id_jenis_palawija' => rand(1, 7),
                'jenis_bantuan' => rand(0, 1) == 1 ? 'bantuan pemerintah' : 'non bantuan pemerintah',
                'tipe_data' => ['panen', 'tanam', 'puso/rusak', 'panen muda', 'panen hijauan pakan ternak'][rand(0, 4)],
                'nilai' => rand(100, 1000),
            ]);

            VerifyPalawija::create([
                'laporan_id' => $laporanPalawija->id,
                'user_id' => $laporanPalawija->user_id,
                'status' => 'tunggu',
                'catatan' => null,
            ]);

            $currentDate->addDay();
        }
    }
}
