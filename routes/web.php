<?php

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
use App\Http\Controllers\PANGAN\KategoriPanganController;
use App\Http\Controllers\PANGAN\LaporanPanganController;
use App\Http\Controllers\PANGAN\DataPanganController;
use App\Http\Controllers\PANGAN\GrafikPanganController;
use App\Http\Controllers\WEB\Pertanian\Data\DataLaporanPadiController;
use App\Http\Controllers\WEB\Pertanian\Data\DataLaporanPalawijaController;
use App\Http\Controllers\WEB\Pertanian\Prediksi\PrediksiPadiController;
use App\Http\Controllers\WEB\Uptd\Akun_Penyuluh\UptdAkunPenyuluhController;
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
                Route::get('palawija', [LaporanUptdPalawijaController::class, 'index']);
            });
            Route::prefix('master')->group(function () {
                Route::get('luas_lahan_wilayah', [LuasLahanWilayahUptdController::class, 'index']);
            });
            Route::get('/dashboard', [DashboardController::class, 'uptd']);
        });
    });

    Route::group(['middleware' => ['can:penyuluh']], function () {
        Route::prefix('penyuluh')->group(function () {
            Route::get('/getDesa', [LaporanPadiController::class, 'getDesa']);
            Route::prefix('create')->group(function () {
                Route::resource('laporan_padi', LaporanPadiController::class);
                Route::resource('laporan_palawija', LaporanPalawijaController::class);
                Route::post('/laporan_palawija/kirim', [LaporanPalawijaController::class, 'kirimkan']);
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
            Route::resource('/create/kategori_pangan', KategoriPanganController::class);

            Route::post('/create/data_pangan/kirim/{id}', [DataPanganController::class, 'kirimkan']);
            Route::resource('/create/data_pangan', DataPanganController::class);
            Route::resource('/data/laporan_pangan', LaporanPanganController::class);

            Route::get('/export/laporan_pangan', [LaporanPanganController::class, 'export'])->name('export.laporan.pangan');
            // Route::get('/grafik/stok_pangan', [GrafikPanganController::class, 'grafikStokPangan']);
            Route::get('/grafik/stok_pangan', [GrafikPanganController::class, 'grafikStokPanganindex']);
            Route::get('/grafik/neraca_pangan', [GrafikPanganController::class, 'grafikNeracaPanganindex']);
            Route::get('/grafik/tren_ketahanan_pangan', [GrafikPanganController::class, 'grafikTrenKetahananPanganindex']);
            Route::get('/grafik/harga_pangan', [GrafikPanganController::class, 'grafikHargaPanganindex']);

        });
    });
});
