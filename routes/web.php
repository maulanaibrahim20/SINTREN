<?php

use App\Http\Controllers\WEB\Auth\LoginController;
use App\Http\Controllers\WEB\Auth\LogoutController;
use App\Http\Controllers\WEB\DashboardController;
use App\Http\Controllers\WEB\Penyuluh\LaporanPadiController;
use App\Http\Controllers\WEB\Penyuluh\Master\JenisPadiController;
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
        return view('welcome');
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

    Route::group(['middleware' => ['can:operator']], function () {
        Route::prefix('operator')->group(function () {
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
            Route::get('/dashboard', [DashboardController::class, 'uptd']);
        });
    });

    Route::group(['middleware' => ['can:penyuluh']], function () {
        Route::prefix('penyuluh')->group(function () {
            Route::prefix('create')->group(function () {
                Route::resource('laporan_padi', LaporanPadiController::class);
            });
            Route::prefix('master')->group(function () {
                Route::resource('jenis_padi', JenisPadiController::class);
            });
            Route::get('/dashboard', [DashboardController::class, 'penyuluh']);
        });
    });
});
