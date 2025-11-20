<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// Public routes
Route::get('/', function () {
    return view('landingpage');
});

Route::get('/login', function () {
    return view('login');
});

// User routes (Firebase auth handled in frontend)
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
Route::post('/api/sync-peternak', [UserController::class, 'syncPeternak'])->name('api.sync-peternak');
Route::get('/histori-data', [UserController::class, 'historiData'])->name('user.histori-data');
Route::get('/grafik-realtime', [UserController::class, 'grafikRealtime'])->name('user.grafik-realtime');
Route::post('/atur-threshold', [UserController::class, 'aturThreshold'])->name('user.atur-threshold');
Route::get('/artikel', [UserController::class, 'bacaArtikel'])->name('user.artikel');
Route::get('/artikel/{id}', [UserController::class, 'detailArtikel'])->name('user.detail-artikel');
Route::get('/forum', [UserController::class, 'forum'])->name('user.forum');
Route::post('/forum', [UserController::class, 'postForum'])->name('user.post-forum');
Route::post('/forum/{id}/reply', [UserController::class, 'replyForum'])->name('user.reply-forum');
Route::get('/prediksi-pertumbuhan', [UserController::class, 'prediksiPertumbuhan'])->name('user.prediksi-pertumbuhan');
Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
Route::get('/price-tracker', [UserController::class, 'priceTracker'])->name('user.price-tracker');
Route::get('/klasifikasi-kualitas', [UserController::class, 'klasifikasiKualitas'])->name('user.klasifikasi-kualitas');

// Admin routes (Firebase auth handled in frontend)
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/kelola-user', [AdminController::class, 'kelolaUser'])->name('admin.kelola-user');
    Route::delete('/user/{email}', [AdminController::class, 'hapusUser'])->name('admin.hapus-user');
    Route::get('/kelola-artikel', [AdminController::class, 'kelolaArtikel'])->name('admin.kelola-artikel');
    Route::post('/artikel', [AdminController::class, 'tambahArtikel'])->name('admin.tambah-artikel');
    Route::put('/artikel/{id}', [AdminController::class, 'editArtikel'])->name('admin.edit-artikel');
    Route::delete('/artikel/{id}', [AdminController::class, 'hapusArtikel'])->name('admin.hapus-artikel');
    Route::get('/kelola-forum', [AdminController::class, 'kelolaForum'])->name('admin.kelola-forum');
    Route::delete('/forum/{id}', [AdminController::class, 'hapusPostForum'])->name('admin.hapus-forum');
});
