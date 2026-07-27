<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Models\DiskusiTugas;
use Illuminate\Http\Request;

/* AUTH */
Route::post('/login', [AuthController::class, 'login']);

/* TUGAS */
Route::get('/tugas', [TugasController::class, 'index']); // atasan
Route::post('/tugas', [TugasController::class, 'store']);
Route::get('/tugas/{id}', [TugasController::class, 'show']);
Route::put('/tugas/{id}', [TugasController::class, 'update']);

/* TUGAS BY USER */
Route::get('/tugas/user/{id}', [TugasController::class, 'userTasks']);

/* DETAIL */
Route::get('/tugas/{id}/detail', [TugasController::class, 'detail']);
Route::post('/tugas/{id}/detail', [TugasController::class, 'storeDetail']);
Route::delete('/tugas-detail/{id}', [TugasController::class, 'deleteDetail']);

/* PROGRESS */
Route::get('/tugas/{id}/progress', [TugasController::class, 'progress']);

/* USER */
Route::get('/users/karyawan', [UserController::class, 'karyawan']);

/* ASSIGN */
Route::post('/tugas/{id}/assign', [TugasController::class, 'assign']);
Route::get('/tugas/{id}/users', [TugasController::class, 'users']);

/* OPTIONAL */
Route::delete('/tugas/{id}/unassign/{userId}', [TugasController::class, 'unassign']);

// LAMPIRAN
Route::post('/tugas/{id}/lampiran', [TugasController::class, 'uploadLampiran']);
Route::get('/tugas/{id}/lampiran', [TugasController::class, 'getLampiran']);
Route::get('/lampiran/{id}/download', [TugasController::class, 'downloadLampiran']);
Route::delete('/lampiran/{id}', [TugasController::class, 'deleteLampiran']);

//DISKUSI
Route::get('/tugas/{id}/diskusi', [TugasController::class, 'getDiskusi']);
Route::post('/tugas/{id}/diskusi', [TugasController::class, 'storeDiskusi']);

Route::get('/dashboard-kinerja', [TugasController::class, 'dashboardKinerja']);
Route::get('/performa-karyawan', [TugasController::class, 'performaKaryawan']);
Route::get('/bagian', [UserController::class, 'getBagian']);

//agar hanya bisa edit rincian tugas dia
Route::put('/tugas-detail/{id}', [TugasController::class,'updateDetail']);

//untuk selesai rincian tugas perlu disetujui atasan
// AJUKAN SELESAI
Route::post(
    '/tugas-detail/{id}/ajukan',
    [TugasController::class, 'ajukanSelesai']
);


// SETUJUI SELESAI
Route::post(
    '/tugas-detail/{id}/setujui',
    [TugasController::class, 'setujuiSelesai']
);

//Detail Kinerja
Route::get(
'/kinerja/karyawan/{id}',
[TugasController::class,'detailKinerjaKaryawan']
);