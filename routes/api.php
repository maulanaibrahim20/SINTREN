<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PadiController;
use App\Http\Controllers\Api\WilayahController;
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

Route::post('/login', [UserController::class, 'login']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::post('/change-password', [UserController::class, 'changePassword'])->middleware('auth:api');

Route::post('/storePadi',[PadiController::class,'store']);
Route::post('/padiShowByUser',[PadiController::class,'showAllByUser']);
Route::post('/showDetailPadiByIdLaporanPadi',[PadiController::class,'showDetailPadiByIdLaporanPadi']);
Route::post('/showDetailPengairanByIdLaporanPadi',[PadiController::class,'showDetailPengairanByIdLaporanPadi']);
Route::post('/deletePadiById',[PadiController::class,'deletePadiById']);
Route::post('/deleteDetailPadiById',[PadiController::class,'deleteDetailPadiById']);
Route::post('/deleteDetailPengairanById',[PadiController::class,'deleteDetailPengairanById']);


Route::get('/pengairan',[PadiController::class,'getPengairan']);
Route::get('/desa',[WilayahController::class,'getDesa']);
Route::get('/kecamatan',[WilayahController::class,'getKecamatan']);

