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
Route::patch('/users/{id}', [UserController::class, 'update']);
Route::get('/getUserById/{id}', [UserController::class, 'getUserById']);
Route::get('/getAssignment/{id}', [UserController::class, 'getAssignment']);
Route::patch('/changePassword/{id}', [UserController::class, 'changePassword']);

Route::post('/padi/store',[PadiController::class,'store']);
Route::patch('/padi/update/{id}',[PadiController::class,'update']);
Route::get('/padi/showByUser/{id}',[PadiController::class,'showAllByUser']);
Route::get('/padi/summary',[PadiController::class,'getDataSummaryByMonth']);
Route::delete('/padi/deletaDetailById/{id}', [PadiController::class, 'deletaDetailById']);

Route::get('/pengairan',[PadiController::class,'getPengairan']);
Route::get('/desa',[WilayahController::class,'getDesa']);
Route::get('/kecamatan',[WilayahController::class,'getKecamatan']);

