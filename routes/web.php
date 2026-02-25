<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

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
    Route::resource('projects', ProjectController::class);
    Route::controller(TaskController::class)->group(function () {
        Route::get('/projects/{project}/tasks', 'index')->name('projects.tasks.index');
        Route::post('/projects/{project}/tasks', 'store')->name('projects.tasks.store');
        Route::get('/projects/{project}/tasks/create', 'create')->name('projects.tasks.create');
        Route::put('/projects/{project}/tasks/{task}', 'update')->name('projects.tasks.update');
        Route::get('/projects/{project}/tasks/{task}/edit', 'edit')->name('projects.tasks.edit');
        Route::patch('/tasks/{task}/status', 'updateStatus')->name('tasks.update-status');
        Route::delete('/projects/{project}/tasks/{task}', 'destroy')->name('projects.tasks.destroy');
    });
    
});
require __DIR__.'/auth.php';
