<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\ChatController;

Auth::routes();

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Prospects
    Route::get('/prospects', [ProspectController::class, 'index'])->name('prospects.index');
    Route::patch('/prospects/{prospect}/status', [ProspectController::class, 'updateStatus'])->name('prospects.status');
    Route::patch('/prospects/{prospect}/assign', [ProspectController::class, 'assign'])->name('prospects.assign');
    Route::post('/prospects/{prospect}/convert', [ProspectController::class, 'convert'])->name('prospects.convert');

    // Chat panel para asesoras
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/{prospect}/send', [ChatController::class, 'sendAdvisorMessage'])->name('chat.send');
});

// Widget API (pública, sin autenticación)
Route::prefix('api/widget')->group(function () {
    Route::post('/start', [ChatController::class, 'widgetStart']);
    Route::post('/message', [ChatController::class, 'widgetMessage']);
    Route::get('/messages', [ChatController::class, 'widgetMessages']);
});

// Ruta para mostrar el widget embebible (demo)
Route::get('/widget-demo', fn() => view('widget.demo'))->name('widget.demo');
