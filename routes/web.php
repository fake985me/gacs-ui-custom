<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\PresetController;
use App\Http\Controllers\ProvisionController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\SetupController;
use Illuminate\Http\Request;
use App\Models\Setting;

// Halaman root: cek setup & login
Route::get('/', function () {
    $setupDone = Setting::get('setup_completed', '0') === '1';

    if (! $setupDone) {
        // Belum setup → paksa ke wizard
        return redirect()->route('setup.show');
    }

    if (Auth::check()) {
        // Sudah setup & sudah login
        return redirect()->route('dashboard');
    }

    // Sudah setup tapi belum login
    return redirect()->route('login');
})->name('root');

// Setup wizard
Route::get('/setup', [SetupController::class, 'show'])->name('setup.show');
Route::post('/setup', [SetupController::class, 'store'])->name('setup.store');

// Logout/signout (POST)
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

// Semua halaman utama dibungkus auth (harus login dulu)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Halaman profile sederhana (untuk menghindari error route('profile.edit'))
    Route::view('/profile', 'profile.edit')->name('profile.edit');

    // DEVICES
    Route::prefix('devices')->name('devices.')->group(function () {
        // Boleh diakses semua user yang sudah login (readonly)
        Route::get('/', [DeviceController::class, 'index'])->name('index');
        Route::get('/{id}', [DeviceController::class, 'show'])->name('show');

        // TR-069 ACTIONS: hanya admin
        Route::middleware('admin')->group(function () {
        // TANPA {id} di URL, deviceId dikirim via POST body
        Route::post('/actions/reboot', [DeviceController::class, 'reboot'])->name('reboot');
        Route::post('/actions/factory-reset', [DeviceController::class, 'factoryReset'])->name('factoryReset');
        Route::post('/actions/wifi', [DeviceController::class, 'updateWifi'])->name('updateWifi');
        Route::post('/actions/download', [DeviceController::class, 'downloadFirmware'])->name('download');
    });
});

    // PRESETS
    Route::prefix('presets')->name('presets.')->group(function () {
        Route::get('/', [PresetController::class, 'index'])->name('index');
        Route::get('/create', [PresetController::class, 'create'])->name('create');
        Route::post('/', [PresetController::class, 'store'])->name('store');
        Route::get('/{name}/edit', [PresetController::class, 'edit'])->name('edit');
        Route::put('/{name}', [PresetController::class, 'update'])->name('update');
        Route::delete('/{name}', [PresetController::class, 'destroy'])->name('destroy');
    });

    // PROVISIONS
    Route::prefix('provisions')->name('provisions.')->group(function () {
        Route::get('/', [ProvisionController::class, 'index'])->name('index');
        Route::get('/create', [ProvisionController::class, 'create'])->name('create');
        Route::post('/', [ProvisionController::class, 'store'])->name('store');
        Route::get('/{name}/edit', [ProvisionController::class, 'edit'])->name('edit');
        Route::put('/{name}', [ProvisionController::class, 'update'])->name('update');
        Route::delete('/{name}', [ProvisionController::class, 'destroy'])->name('destroy');
    });

    // FILES
    Route::prefix('files')->name('files.')->group(function () {
        Route::get('/', [FileController::class, 'index'])->name('index');
        Route::get('/upload', [FileController::class, 'create'])->name('create');
        Route::post('/upload', [FileController::class, 'store'])->name('store');
        Route::delete('/{name}', [FileController::class, 'destroy'])->name('destroy');
    });

    // TASKS / LOGS / CONFIG
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('/config', [ConfigController::class, 'index'])->name('config.index');
});

// Catatan:
// Kalau kamu pakai Laravel Breeze / auth bawaan,
// pastikan di bawah sini ada:
//
require __DIR__.'/auth.php';
