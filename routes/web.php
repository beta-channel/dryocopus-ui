<?php

use App\Http\Controllers\ExecutionController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DryocopusController;
use Illuminate\Support\Facades\Route;

Route::any('/login', [DryocopusController::class, 'login'])->name('login');
Route::get('/logout', [DryocopusController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Route::get('/', [DryocopusController::class, 'dashboard'])->name('dashboard');
    Route::get('/', fn() => redirect()->route('tasks'))->name('dashboard');
    Route::get('/security', [DryocopusController::class, 'security'])->name('security');
    Route::post('/password/change', [DryocopusController::class, 'changePassword'])->name('password.change');

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::get('/task/create', [TaskController::class, 'edit'])->name('task.create');
    Route::get('/task/{task_id}/detail', [TaskController::class, 'detail'])->name('task.detail')->whereNumber('task_id');
    Route::get('/task/{task_id}/edit', [TaskController::class, 'edit'])->name('task.edit')->whereNumber('task_id');
    Route::post('/task/create', [TaskController::class, 'update'])->name('task.create');
    Route::post('/task/{task_id}/update', [TaskController::class, 'update'])->name('task.update')->whereNumber('task_id');
    Route::post('/task/delete', [TaskController::class, 'delete'])->name('task.delete');
    Route::post('/task/startup', [TaskController::class, 'startup'])->name('task.startup');
    Route::post('/task/active', [TaskController::class, 'active'])->name('task.active');
    Route::post('/task/stop', [TaskController::class, 'stop'])->name('task.stop');

    Route::get('/plans', [PlanController::class, 'index'])->name('plans');
    Route::post('/plan/create', [PlanController::class, 'create'])->name('plan.create');
    Route::post('/plan/delete', [PlanController::class, 'delete'])->name('plan.delete');

    Route::get('/executions', [ExecutionController::class, 'index'])->name('executions');
});
