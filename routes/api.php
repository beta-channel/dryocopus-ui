<?php

use App\Http\Controllers\ExecutionController;
use Illuminate\Support\Facades\Route;

Route::post('/execution/start', [ExecutionController::class, 'start'])->name('execution.start');
Route::post('/execution/finish', [ExecutionController::class, 'finish'])->name('execution.finish');
