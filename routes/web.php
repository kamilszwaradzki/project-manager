<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
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
});

Route::middleware('auth')->group(function () {
    Route::controller(ProjectController::class)->group(function () {
        Route::get('/projects', 'index')->name('projects.index');
        Route::get('/projects/new', 'create')->name('projects.create');
        Route::get('/projects/{project}', 'show')->name('projects.show');
        Route::post('/projects', 'store')->name('projects.store');
        Route::get('/projects/{project}/edit', 'edit')->name('projects.edit');
        Route::put('/projects/{project}', 'update')->name('projects.update');
        Route::delete('/projects/{project}', 'destroy')->name('projects.destroy');
    });
});
require __DIR__.'/auth.php';
