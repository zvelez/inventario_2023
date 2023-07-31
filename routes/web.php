<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/users', [UserController::class, 'index'])->middleware(['auth', 'verified'])->name('users');
Route::get('/users/create', [UserController::class, 'create'])->middleware(['auth', 'verified'])->name('users.create');
Route::post('/users/create', [UserController::class, 'store'])->middleware(['auth', 'verified']);
Route::get('/users/{id}', [UserController::class, 'update'])->middleware(['auth', 'verified'])->name('users.update');
Route::put('/users/{id}', [UserController::class, 'edit'])->middleware(['auth', 'verified']);
Route::delete('/users/{id}', [UserController::class, 'delete'])->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
