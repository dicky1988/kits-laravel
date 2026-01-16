<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\SwitchRoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

/*Route::middleware(['auth', 'permission:menu.admin'])
    ->get('/users', [UserController::class, 'index'])
    ->name('users.index');*/

Route::post('/switch-role', [SwitchRoleController::class, 'switch'])
    ->name('switch.role');

Route::middleware(['auth'])
    ->get('/users', [UserController::class, 'index'])
    ->middleware('active.permission:menu.admin')->name('users.index');
Route::middleware(['auth'])
    ->post('/users/sync-tte', [UserController::class, 'sync'])
    ->name('users.sync.tte');
Route::middleware(['auth'])->patch('/users/{id}/activate', [UserController::class, 'activateViaApi'])
    ->name('users.api.activate');
Route::middleware(['auth'])->patch('/users/{id}/deactivate', [UserController::class, 'deactivateViaApi'])
    ->name('users.api.deactivate');
Route::middleware(['auth'])->patch('/users/{id}/activate/sync', [UserController::class, 'activateSyncViaApi'])
    ->name('users.api.activate.sync');
Route::middleware(['auth'])->patch('/users/{id}/deactivate/sync', [UserController::class, 'deactivateSyncViaApi'])
    ->name('users.api.deactivate.sync');

Route::middleware(['auth'])->patch('/users/{id}/activate/ujikom/{value}', [UserController::class, 'activateUjikomViaApi'])
    ->name('users.api.activate.ujikom');
Route::middleware(['auth'])->patch('/users/{id}/activate/sertifikat/{value}', [UserController::class, 'activateSertifikatViaApi'])
    ->name('users.api.activate.sertifikat');
Route::middleware(['auth'])->patch('/users/{id}/activate/bangkom/{value}', [UserController::class, 'activateBangkomViaApi'])
    ->name('users.api.activate.bangkom');
Route::middleware(['auth'])->patch('/users/{id}/activate/skp/{value}', [UserController::class, 'activateSkpViaApi'])
    ->name('users.api.activate.skp');
Route::middleware(['auth'])->patch('/users/{id}/activate/bidang3/{value}', [UserController::class, 'activateBidang3ViaApi'])
    ->name('users.api.activate.bidang3');

require __DIR__.'/auth.php';
