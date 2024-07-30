<?php

use App\Http\Controllers\ExecutionController;
use Illuminate\Support\Facades\Route;

Route::post('/execution/start', [ExecutionController::class, 'start'])->name('execution.start');
Route::post('/execution/finish', [ExecutionController::class, 'finish'])->name('execution.finish');
Route::post('/execution/succeed', [ExecutionController::class, 'succeed'])->name('execution.succeed');
Route::post('/execution/failed', [ExecutionController::class, 'failed'])->name('execution.failed');
