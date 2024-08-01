<?php

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Admin\AdminPadiController;
use App\Http\Controllers\Api\Admin\AdminPalawijaController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WilayahController;
use App\Http\Controllers\Api\Pangan\JenisPanganController;
use App\Http\Controllers\Api\Pangan\SubjenisPanganController;
use App\Http\Controllers\Api\Pangan\DataPanganController;
use App\Http\Controllers\Api\Penyuluh\PadiController;
use App\Http\Controllers\Api\Penyuluh\PalawijaController;
use App\Http\Controllers\Api\Penyuluh\PenyuluhController;
use App\Http\Controllers\PANGAN\PasarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// USER END POINT
Route::post('/login', [UserController::class, 'login']);
Route::patch('/users/{id}', [UserController::class, 'update']);
Route::get('/getUserById/{id}', [UserController::class, 'getUserById']);
Route::patch('/changePassword/{id}', [UserController::class, 'changePassword']);
Route::patch('/forgotPassword/{id}', [UserController::class, 'forgotPassword']);
Route::post('/checkEmail', [UserController::class, 'searchNotelp']);

// PENYULUH END POINT
Route::get('/penyuluh/getDesa/{id}', [PenyuluhController::class, 'getDesa']);

// PADI END POINT
Route::get('/pengairan', [PadiController::class, 'getPengairan']);
Route::get('/padi', [PadiController::class, 'getPadi']);
Route::post('/padi/store', [PadiController::class, 'store']);
Route::patch('/padi/update/{id}', [PadiController::class, 'update']);
Route::get('/padi/showByUser/{id}', [PadiController::class, 'showAllByUser']);
Route::delete('/padi/deleteDetailById/{id}', [PadiController::class, 'deleteDetailById']);

// PALAWIJA END POINT
Route::get('/palawija', [PalawijaController::class, 'getJenisPalawija']);
Route::post('/palawija/store', [PalawijaController::class, 'store']);
Route::patch('/palawija/update/{id}', [PalawijaController::class, 'update']);
Route::get('/palawija/showByUser/{id}', [PalawijaController::class, 'showAllByUser']);
Route::delete('/palawija/deleteDetailById/{id}', [PalawijaController::class, 'deleteDetailById']);

// ADMIN END POINT
Route::get('/admin/getDesa/{id}', [AdminController::class, 'getDesa']);
Route::get('/padi/showByKecamatan/{id}', [AdminPadiController::class, 'showAllByKecamatan']);
Route::get('/palawija/showByKecamatan/{id}', [AdminPalawijaController::class, 'showAllByKecamatan']);
Route::patch('/verify/{id}', [AdminController::class, 'verify']);
Route::get('/admin/getPenyuluh/{id}', [AdminController::class, 'getPenyuluh']);
Route::post('/admin/addPenugasan/', [AdminController::class, 'addPenugasan']);
Route::delete('/admin/deletePenugasan/{id}', [AdminController::class, 'deletePenugasan']);


Route::get('/desa',[WilayahController::class,'getDesa']);
Route::get('/kecamatan',[WilayahController::class,'getKecamatan']);

// PANGAN END POINT
Route::get('/pangan/jenis_pangan',[JenisPanganController::class,'getJenisPangan']);
Route::get('pangan/subjenis_pangan/{id_jenis_pangan}', [SubjenisPanganController::class, 'getSubjenisPangan']);
Route::get('/pasar', [DataPanganController::class, 'getPasar']);
Route::get('/pangan/grafik-data/{id}', [DataPanganController::class, 'grafikData']);
Route::post('/pangan/store', [DataPanganController::class, 'store']);
Route::put('/pangan/update/{id}',[DataPanganController::class,'update']);
Route::get('/pangan/showByUser/{id}',[DataPanganController::class,'showAllByUser']);
Route::delete('/pangan/deleteDetailById/{id}', [DataPanganController::class, 'deleteDetailById']);


Route::get('/pasar', [PasarController::class, 'getPasar']);
