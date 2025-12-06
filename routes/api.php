<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sekolah\SekolahApiController;
use App\Http\Controllers\Api\ComputerLogController;
use App\Http\Controllers\SuperAdmin\ComputerManagerController;


Route::post('/update-pc-password', [ComputerLogController::class, 'store']);
Route::post('/check-command', [ComputerManagerController::class, 'checkCommand']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/sekolah/scan', [SekolahApiController::class, 'scanAbsensi']);
