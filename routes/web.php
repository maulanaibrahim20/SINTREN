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
use App\Http\Controllers\WEB\Operator\Tanaman\Kategori\KategoriTanamanPalawijaController;
use App\Http\Controllers\WEB\Operator\User\UptdController;
use App\Http\Controllers\WEB\Operator\User\PenyuluhController;
use App\Http\Controllers\WEB\Penyuluh\LaporanPadiController;
use App\Http\Controllers\WEB\Penyuluh\LaporanPalawijaController;
use App\Http\Controllers\WEB\Penyuluh\Master\JenisPadiController;
use App\Http\Controllers\WEB\Penyuluh\Master\LuasLahanWilayahUptdController;
use App\Http\Controllers\WEB\Uptd\LaporanUptdPadiController;
use App\Http\Controllers\WEB\Uptd\LaporanUptdPalawijaController;
use App\Http\Controllers\WEB\Uptd\user\UptdPenyuluhController;
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

    Route::group(['middleware' => ['can:operator']], function () {
        Route::prefix('operator')->group(function () {
            Route::prefix('user')->group(function () {
                Route::resource('pertanian', PertanianController::class);
                Route::resource('uptd', UptdController::class);
                Route::resource('penyuluh', PenyuluhController::class);
            });
            Route::prefix('kategori')->group(function () {
                Route::resource('tanaman_palawija', KategoriTanamanPalawijaController::class);
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
        });
    });

    Route::group(['middleware' => ['can:uptd']], function () {
        Route::prefix('uptd')->group(function () {
            Route::resource('pengguna/penyuluh', UptdPenyuluhController::class);
            Route::post('pengguna/penyuluh/penugasan', [UptdPenyuluhController::class, 'penugasan']);
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
    Route::group(['middleware' => ['can:dinas_pangan']], function () {
        Route::prefix('dinas_pangan')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'dinas_pangan']);
        });
    });
});
