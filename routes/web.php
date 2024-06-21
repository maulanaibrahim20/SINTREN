<?php

use App\Http\Controllers\ImportExportController;
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
use App\Http\Controllers\WEB\Penyuluh\LaporanPadiController;
use App\Http\Controllers\WEB\Penyuluh\LaporanPalawijaController;
use App\Http\Controllers\WEB\Penyuluh\Master\LuasLahanWilayahUptdController;
use App\Http\Controllers\WEB\Uptd\LaporanUptdPadiController;
use App\Http\Controllers\WEB\Uptd\LaporanUptdPalawijaController;
use App\Http\Controllers\PANGAN\UserPasarController;
use App\Http\Controllers\PANGAN\PasarController;
use App\Http\Controllers\WEB\Penyuluh\EditProfileController;
use App\Http\Controllers\WEB\Pertanian\Data\DataLaporanPadiController;
use App\Http\Controllers\WEB\Pertanian\Data\DataLaporanPalawijaController;
use App\Http\Controllers\WEB\Pertanian\Prediksi\PrediksiPadiController;
use App\Http\Controllers\WEB\Pertanian\Prediksi\PrediksiSpPadiController;
use App\Http\Controllers\WEB\Uptd\Akun_Penyuluh\UptdAkunPenyuluhController;
use App\Http\Controllers\WEB\Uptd\EditProfileUptdController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/', function () {
        return view('landing');
    });
    Route::prefix('login')->name('login.')->group(function () {
        Route::get('/', [LoginController::class, 'index'])
            ->name('index');
        Route::post('/', [LoginController::class, 'process'])
            ->name('process');
    });
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
                Route::get('/padiSp', [PrediksiSpPadiController::class, 'indexSP']);
                Route::post('/padiSp', [PrediksiSpPadiController::class, 'menghitungRegresiSP']);
            });
            Route::get('data_padi', [DataLaporanPadiController::class, 'index']);
            Route::get('data_padi/show/{id}', [DataLaporanPadiController::class, 'show']);
            Route::post('data_padi/filter', [DataLaporanPadiController::class, 'filter']);
            Route::get('data_padi/exportPdf', [DataLaporanPadiController::class, 'exportPdf']);

            Route::get('data_palawija', [DataLaporanPalawijaController::class, 'index']);
            Route::get('data_palawija/show/{id}', [DataLaporanPalawijaController::class, 'show']);
            Route::post('data_palawija/filter', [DataLaporanPalawijaController::class, 'filter']);
            Route::get('data_palawija/exportPdf', [DataLaporanPalawijaController::class, 'exportPdf']);
        });
    });

    Route::group(['middleware' => ['can:uptd']], function () {
        Route::prefix('uptd')->group(function () {
            Route::resource('pengguna/penyuluh', UptdAkunPenyuluhController::class);
            Route::post('pengguna/penyuluh/penugasan', [UptdAkunPenyuluhController::class, 'penugasan']);
            Route::put('pengguna/penyuluh/penugasan/{id}', [UptdAkunPenyuluhController::class, 'updatePenugasan']);
            Route::prefix('laporan')->group(function () {
                Route::get('padi', [LaporanUptdPadiController::class, 'index']);
                Route::get('padi/showDetailLaporan/{desa_id}', [LaporanUptdPadiController::class, 'showDetailLaporanKecamatan']);
                Route::post('padi/changeStatus/{id}', [LaporanUptdPadiController::class, 'changeStatus']);

                Route::get('palawija', [LaporanUptdPalawijaController::class, 'index']);
                Route::get('palawija/showDetailLaporan/{desa_id}', [LaporanUptdPalawijaController::class, 'showDetailLaporanKecamatan']);
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
            Route::resource('/pasar/data_pasar', PasarController::class);
        });
    });
});
