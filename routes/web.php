<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\WEB\Auth\LoginController;
use App\Http\Controllers\WEB\Auth\LogoutController;
use App\Http\Controllers\WEB\DashboardController;
use App\Http\Controllers\WEB\GetWilayahController;
use App\Http\Controllers\WEB\Operator\Master\LuasLahanWilayahController;
use App\Http\Controllers\WEB\Operator\Master\RoleController;
use App\Http\Controllers\WEB\Operator\Tanaman\TanamanPadiController;
use App\Http\Controllers\WEB\Operator\Tanaman\TanamanPalawijaController;
use App\Http\Controllers\WEB\Operator\Master\WilayahController;
use App\Http\Controllers\WEB\Operator\User\PertanianController;
use App\Http\Controllers\WEB\Operator\Master\PengairanController;
use App\Http\Controllers\WEB\Operator\User\UptdController;
use App\Http\Controllers\WEB\Operator\User\PenyuluhController;
use App\Http\Controllers\WEB\Operator\User\PanganController;
use App\Http\Controllers\WEB\Operator\User\PetugasPasarController;
use App\Http\Controllers\WEB\Penyuluh\LaporanPadiController;
use App\Http\Controllers\WEB\Penyuluh\LaporanPalawijaController;
use App\Http\Controllers\WEB\Penyuluh\Master\LuasLahanWilayahUptdController;
use App\Http\Controllers\WEB\Uptd\LaporanUptdPadiController;
use App\Http\Controllers\WEB\Uptd\LaporanUptdPalawijaController;

use App\Http\Controllers\PANGAN\UserPasarController;
use App\Http\Controllers\PANGAN\PasarController;
use App\Http\Controllers\PANGAN\JenisPanganController;
use App\Http\Controllers\PANGAN\SubjenisPanganController;
use App\Http\Controllers\PANGAN\LaporanPanganController;
use App\Http\Controllers\PANGAN\DataPanganController;
use App\Http\Controllers\PANGAN\GrafikPanganController;
use App\Http\Controllers\PANGAN\EditProfilePanganController;


use App\Http\Controllers\WEB\Auth\ForgotPasswordController;
use App\Http\Controllers\WEB\Auth\NewPasswordController;
use App\Http\Controllers\WEB\Auth\VerificationController;
use App\Http\Controllers\WEB\Penyuluh\EditProfileController;

use App\Http\Controllers\WEB\Pertanian\Data\DataLaporanPadiController;
use App\Http\Controllers\WEB\Pertanian\Data\DataLaporanPalawijaController;
use App\Http\Controllers\WEB\Pertanian\EditProfilePertanianController;
use App\Http\Controllers\WEB\Pertanian\Prediksi\PrediksiPadiController;
use App\Http\Controllers\WEB\Pertanian\Prediksi\PrediksiPalawijaController;
use App\Http\Controllers\WEB\Uptd\Akun_Penyuluh\UptdAkunPenyuluhController;
use App\Http\Controllers\WEB\Uptd\EditProfileUptdController;
use App\Http\Controllers\WEB\Uptd\LaporanNotVerifyController;
use Illuminate\Support\Facades\Route;


use Carbon\Carbon;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['guest'])->group(function () {

    Route::get('/', [AppController::class, 'index']);
    Route::post('/kotakSaran', [AppController::class, 'kotakSaran']);

    Route::prefix('login')->name('login.')->group(function () {
        Route::get('/', [LoginController::class, 'index'])
            ->name('index');
        Route::post('/', [LoginController::class, 'process'])
            ->name('process');
    });

    Route::get('lupa_password', [ForgotPasswordController::class, 'index']);
    Route::post('lupa_password', [ForgotPasswordController::class, 'sendEmail']);

    Route::prefix('new-password')->name('new-password.')->group(function () {
        Route::get('/', [NewPasswordController::class, 'index'])->name('index');
        Route::post('/', [NewPasswordController::class, 'process'])->name('process');
    });

    Route::get('/verification', VerificationController::class)
        ->name('verification');
});

Route::middleware(['auth'])->name('web.')->group(function () {
    Route::get('/logout', LogoutController::class)
        ->name('auth.logout');
});




Route::middleware(['autentikasi'])->group(function () {

    Route::get('ambil_desa', [GetWilayahController::class, 'ambil_desa']);
    Route::get('ambil_desa_filtering', [GetWilayahController::class, 'ambil_desa_filtering']);


    Route::group(['middleware' => ['can:operator']], function () {
        Route::prefix('operator')->group(function () {
            Route::prefix('user')->group(function () {
                Route::resource('pertanian', PertanianController::class);
                Route::resource('uptd', UptdController::class);
                Route::resource('penyuluh', PenyuluhController::class);
                Route::resource('pangan', PanganController::class);
                Route::resource('pasar', PetugasPasarController::class);
            });
            Route::prefix('tanaman')->group(function () {
                Route::resource('padi', TanamanPadiController::class);
                Route::resource('palawija', TanamanPalawijaController::class);
            });
            Route::prefix('master')->group(function () {
                Route::get('wilayah', [WilayahController::class, 'index']);
                Route::get('wilayah/view/{id}', [WilayahController::class, 'view_desa']);
                Route::resource('luas_lahan_wilayah', LuasLahanWilayahController::class);
                Route::get('role', [RoleController::class, 'index']);
                Route::resource('pengairan', PengairanController::class);
                Route::resource('data_pasar', PasarController::class);
            });
            Route::get('/dashboard', [DashboardController::class, 'operator']);
        });
    });

    Route::group(['middleware' => ['can:pertanian']], function () {
        Route::prefix('pertanian')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'pertanian']);
            Route::prefix('prediksi')->group(function () {
                Route::get('/padi', [PrediksiPadiController::class, 'index']);
                Route::post('/padi', [PrediksiPadiController::class, 'menghitungRegresi']);

                Route::get('/palawija', [PrediksiPalawijaController::class, 'index']);
                Route::post('/palawija', [PrediksiPalawijaController::class, 'menghitungRegresiPalawija']);
            });
            Route::get('data_padi', [DataLaporanPadiController::class, 'index']);
            Route::get('data_padi/show/{id}', [DataLaporanPadiController::class, 'show']);
            Route::post('data_padi/filter', [DataLaporanPadiController::class, 'filter']);
            Route::get('data_padi/exportPdf', [DataLaporanPadiController::class, 'exportPdf']);

            Route::get('data_palawija', [DataLaporanPalawijaController::class, 'index']);
            Route::get('data_palawija/show/{id}', [DataLaporanPalawijaController::class, 'show']);
            Route::post('data_palawija/filter', [DataLaporanPalawijaController::class, 'filter']);
            Route::get('data_palawija/exportPdf', [DataLaporanPalawijaController::class, 'exportPdf']);
            Route::prefix('pengaturan')->group(function () {
                Route::get('editProfile', [EditProfilePertanianController::class, 'index']);
                Route::put('editProfile/{id}', [EditProfilePertanianController::class, 'update']);
                Route::put('editPassword/{id}', [EditProfilePertanianController::class, 'updatePassword']);
            });
        });
    });

    Route::group(['middleware' => ['can:uptd']], function () {
        Route::prefix('uptd')->group(function () {

            Route::get('/laporanNotVerify', [LaporanNotVerifyController::class, 'index']);
            Route::post('/laporanNotVerify/changeStatus/{id}', [LaporanNotVerifyController::class, 'changeStatus']);

            Route::resource('pengguna/penyuluhUptd', UptdAkunPenyuluhController::class);
            Route::post('pengguna/penyuluh/penugasan', [UptdAkunPenyuluhController::class, 'penugasan']);
            Route::put('pengguna/penyuluh/penugasan/{id}', [UptdAkunPenyuluhController::class, 'updatePenugasan']);
            Route::prefix('laporan')->group(function () {
                Route::get('padi', [LaporanUptdPadiController::class, 'index']);
                Route::get('padi/showDetailLaporan/{desa_id}/{month_year}', [LaporanUptdPadiController::class, 'showDetailLaporanKecamatan']);
                Route::post('padi/changeStatus/{id}', [LaporanUptdPadiController::class, 'changeStatus']);

                Route::get('palawija', [LaporanUptdPalawijaController::class, 'index']);
                Route::get('palawija/showDetailLaporan/{desa_id}/{month_year}', [LaporanUptdPalawijaController::class, 'showDetailLaporanKecamatan']);
                Route::post('palawija/changeStatus/{id}', [LaporanUptdPalawijaController::class, 'changeStatus']);
            });
            Route::prefix('master')->group(function () {
                Route::get('luas_lahan_wilayah', [LuasLahanWilayahUptdController::class, 'index']);
            });
            Route::prefix('pengaturan')->group(function () {
                Route::get('editProfile', [EditProfileUptdController::class, 'index']);
                Route::put('editProfile/{id}', [EditProfileUptdController::class, 'update']);
                Route::put('editPassword/{id}', [EditProfileUptdController::class, 'updatePassword']);
            });
            Route::get('/dashboard', [DashboardController::class, 'uptd']);
        });
    });

    Route::group(['middleware' => ['can:penyuluh']], function () {
        Route::prefix('penyuluh')->group(function () {
            Route::get('/getDesa', [LaporanPadiController::class, 'getDesa']);
            Route::prefix('create')->group(function () {
                Route::resource('laporan_padi', LaporanPadiController::class);
                Route::get('laporan_padi/show/{desa_id}', [LaporanPadiController::class, 'showDesa']);
                Route::resource('laporan_palawija', LaporanPalawijaController::class);
                Route::get('laporan_palawija/show/{desa_id}', [LaporanPalawijaController::class, 'showDesa']);
                Route::post('/laporan_palawija/kirim', [LaporanPalawijaController::class, 'kirimkan']);
            });
            Route::prefix('pengaturan')->group(function () {
                Route::get('editProfile', [EditProfileController::class, 'index']);
                Route::put('editProfile/{id}', [EditProfileController::class, 'update']);
                Route::put('editPassword/{id}', [EditProfileController::class, 'updatePassword']);
            });
            // Route::prefix('master')->group(function () {
            //     Route::get('luas_lahan_wilayah', [LuasLahanWilayahUptdController::class, 'index']);
            // });
            Route::get('/dashboard', [DashboardController::class, 'penyuluh']);
        });
    });

    // yuan diana
    Route::group(['middleware' => ['can:pangan']], function () {
        Route::prefix('pangan')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'pangan']);
            Route::resource('/user/pasar', UserPasarController::class);
            Route::resource('/create/data_pasar', PasarController::class);
            Route::resource('/create/jenis_pangan', JenisPanganController::class);
            Route::resource('/create/subjenis_pangan', SubjenisPanganController::class);

            // Route::post('/create/data_pangan/kirim/{id}', [DataPanganController::class, 'kirimkan']);
            Route::resource('/create/data_pangan', DataPanganController::class);
            Route::resource('/data/laporan_harian', LaporanPanganController::class);
            Route::get('/data/laporan_bulanan', [LaporanPanganController::class, 'bulanan'])->name('laporan_bulanan.bulanan');
            Route::get('/data/laporan_tahunan', [LaporanPanganController::class, 'tahunan'])->name('laporan_tahunan.tahunan');
            Route::get('/export/laporan/harian', [LaporanPanganController::class, 'exportHarian'])->name('export.laporan.harian');
            Route::get('/export/laporan/bulanan', [LaporanPanganController::class, 'exportBulanan'])->name('export.laporan.bulanan');
            Route::get('/export/laporan/tahunan', [LaporanPanganController::class, 'exportTahunan'])->name('export.laporan.tahunan');
            // Route::get('/export/laporan_pangan', [LaporanPanganController::class, 'export'])->name('export.laporan.pangan');
            // Route::get('/grafik/stok_pangan', [GrafikPanganController::class, 'grafikStokPangan']);
            Route::get('/grafik/harian', [GrafikPanganController::class, 'grafikHarianIndex'])->name('grafik.harian.index');
            Route::get('/grafik/bulanan', [GrafikPanganController::class, 'grafikBulananindex'])->name('grafik.bulanan.index');
            Route::get('/grafik/tahunan', [GrafikPanganController::class, 'grafikTahunanindex'])->name('grafik.tahunan.index');
            Route::prefix('pengaturan')->group(function () {
                Route::get('editProfile', [EditProfilePanganController::class, 'index'])->name('editProfile');
                Route::put('editProfile/{id}', [EditProfilePanganController::class, 'update'])->name('updateProfile');
                Route::put('editPassword/{id}', [EditProfilePanganController::class, 'updatePassword'])->name('updatePassword');


            });
        });
    });


});


Route::get('/update-notification-status', function () {
    // Inisialisasi tanggal hari ini
    $tanggalHariIni = Carbon::today();

    // Ambil semua user_id dari tabel 'notifications'
    $notifications = DB::table('notifications')->get();

    foreach ($notifications as $notification) {
        $userId = $notification->user_id;

        // Cek apakah user_id ini memiliki entri di 'laporan_pangans' untuk hari ini
        $userHasReportedToday = DB::table('laporan_pangans')
            ->where('user_id', $userId)
            ->whereDate('created_at', $tanggalHariIni)
            ->exists();

        if ($userHasReportedToday) {
            // Update status di 'notifications' menjadi 1 (sudah input data)
            DB::table('notifications')
                ->where('user_id', $userId)
                ->update(['status' => 1]);
        } else {
            // Update status di 'notifications' menjadi 0 (belum input data)
            DB::table('notifications')
                ->where('user_id', $userId)
                ->update(['status' => 0]);
        }
    }

    // Menampilkan pesan untuk memastikan skrip berjalan
    return "Update status di tabel notifications telah berhasil dieksekusi.";
});
