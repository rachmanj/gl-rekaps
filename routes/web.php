<?php

use App\Http\Controllers\DailyBalanceController;
use App\Http\Controllers\DailyJournalController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TransitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('transit')->name('transit.')->group(function () {
    Route::get('/', [TransitController::class, 'index'])->name('index');
    Route::post('/upload', [TransitController::class, 'upload'])->name('upload');
});

Route::prefix('daily-journal')->name('daily-journal.')->group(function () {
    Route::get('/insert-journals', [DailyJournalController::class, 'insertJournals'])->name('insert-journals');
});

Route::prefix('daily-balance')->name('daily-balance.')->group(function () {
    Route::post('/store', [DailyBalanceController::class, 'store'])->name('store');
});

Route::get('test', [TestController::class, 'index'])->name('test.index');
