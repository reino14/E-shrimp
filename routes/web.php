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
Route::post('/atur-threshold', [UserController::class, 'aturThreshold'])->name('user.atur-threshold');
Route::delete('/threshold/{thresholdId}', [UserController::class, 'hapusThreshold'])->name('user.hapus-threshold');
Route::post('/mulai-monitoring', [UserController::class, 'mulaiMonitoring'])->name('user.mulai-monitoring');
Route::get('/monitoring-suggestions', [UserController::class, 'getMonitoringSuggestions'])->name('user.monitoring-suggestions');
Route::get('/monitoring-session/{sessionId}', [UserController::class, 'getMonitoringSessionDetail'])->name('user.monitoring-session-detail');
Route::delete('/monitoring-session/{sessionId}', [UserController::class, 'deleteMonitoringSession'])->name('user.delete-monitoring-session');
Route::post('/monitoring-session/{sessionId}/pause', [UserController::class, 'pauseMonitoringSession'])->name('user.pause-monitoring-session');
Route::post('/monitoring-session/{sessionId}/resume', [UserController::class, 'resumeMonitoringSession'])->name('user.resume-monitoring-session');
Route::post('/monitoring-session/{sessionId}/restart', [UserController::class, 'restartMonitoringSession'])->name('user.restart-monitoring-session');
Route::post('/api/simulate-realtime-data', [UserController::class, 'simulateRealtimeData'])->name('api.simulate-realtime-data');
Route::get('/api/notifications', [UserController::class, 'getNotifications'])->name('api.notifications');
Route::post('/api/create-notification', [UserController::class, 'createNotification'])->name('api.create-notification');
// Clear all must be before the parameterized route to avoid route conflict
Route::delete('/api/notifications/clear-all', [UserController::class, 'clearAllNotifications'])->name('api.clear-all-notifications');
Route::delete('/api/notifications/{notificationId}', [UserController::class, 'deleteNotification'])->name('api.delete-notification');
Route::get('/api/thresholds', [UserController::class, 'getThresholds'])->name('api.thresholds');
Route::get('/api/sensor-data', [UserController::class, 'getSensorData'])->name('api.sensor-data');
Route::get('/manajemen-kapal', [UserController::class, 'manajemenKapal'])->name('user.manajemen-kapal');
Route::post('/manajemen-kapal', [UserController::class, 'tambahKapal'])->name('user.tambah-kapal');
Route::put('/manajemen-kapal/{robotId}', [UserController::class, 'updateKapal'])->name('user.update-kapal');
Route::delete('/manajemen-kapal/{robotId}', [UserController::class, 'hapusKapal'])->name('user.hapus-kapal');
Route::get('/manajemen-kolam', [UserController::class, 'manajemenKolam'])->name('user.manajemen-kolam');
Route::post('/manajemen-kolam', [UserController::class, 'tambahKolam'])->name('user.tambah-kolam');
Route::delete('/manajemen-kolam/{kolamId}', [UserController::class, 'hapusKolam'])->name('user.hapus-kolam');
Route::get('/artikel', [UserController::class, 'bacaArtikel'])->name('user.artikel');
Route::get('/artikel/{id}', [UserController::class, 'detailArtikel'])->name('user.detail-artikel');
Route::get('/forum', [UserController::class, 'forum'])->name('user.forum');
Route::post('/forum', [UserController::class, 'postForum'])->name('user.post-forum');
Route::post('/forum/{id}/reply', [UserController::class, 'replyForum'])->name('user.reply-forum');
Route::get('/prediksi-pertumbuhan', [UserController::class, 'prediksiPertumbuhan'])->name('user.prediksi-pertumbuhan');
Route::post('/api/predict-growth', [UserController::class, 'predictGrowth'])->name('api.predict-growth');
Route::get('/api/training-progress', [UserController::class, 'getTrainingProgress'])->name('api.training-progress');
Route::get('/profil', [UserController::class, 'profil'])->name('user.profil');
Route::get('/price-tracker', [UserController::class, 'priceTracker'])->name('user.price-tracker');

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
    Route::get('/peternak/{email}', [AdminController::class, 'detailPeternak'])->name('admin.detail-peternak');
    Route::delete('/kolam/{kolamId}', [AdminController::class, 'hapusKolam'])->name('admin.hapus-kolam');
    Route::delete('/monitoring-session/{sessionId}', [AdminController::class, 'hapusMonitoringSession'])->name('admin.hapus-monitoring-session');
});
