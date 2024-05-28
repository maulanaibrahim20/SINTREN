<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PadiController;
use App\Http\Controllers\Api\PalawijaController;
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

// USER END POINT
Route::post('/login', [UserController::class, 'login']);
Route::patch('/users/{id}', [UserController::class, 'update']);
Route::get('/getUserById/{id}', [UserController::class, 'getUserById']);
Route::get('/getAssignment/{id}', [UserController::class, 'getAssignment']);
Route::patch('/changePassword/{id}', [UserController::class, 'changePassword']);

// PADI END POINT
Route::get('/pengairan',[PadiController::class,'getPengairan']);
Route::get('/padi',[PadiController::class,'getPadi']);
Route::post('/padi/store',[PadiController::class,'store']);
Route::patch('/padi/update/{id}',[PadiController::class,'update']);
Route::get('/padi/showByUser/{id}',[PadiController::class,'showAllByUser']);
Route::delete('/padi/deletaDetailById/{id}', [PadiController::class, 'deletaDetailById']);
Route::get('/padi/summary',[PadiController::class,'getDataSummaryByMonth']);

// PALAWIJA END POINT
Route::get('/palawija',[PalawijaController::class,'getJenisPalawija']);
Route::post('/palawija/store',[PalawijaController::class,'store']);
Route::patch('/palawija/update/{id}',[PalawijaController::class,'update']);
Route::get('/palawija/showByUser/{id}',[PalawijaController::class,'showAllByUser']);
Route::delete('/palawija/deletaDetailById/{id}', [PalawijaController::class, 'deletaDetailById']);


Route::get('/desa',[WilayahController::class,'getDesa']);
Route::get('/kecamatan',[WilayahController::class,'getKecamatan']);

