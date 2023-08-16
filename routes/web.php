<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Supplier\SupplierController;

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

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/dashboard', function () {
      return Inertia::render('Dashboard');
  })->name('dashboard');

  Route::get('/users', [UserController::class, 'index'])->name('users');
  Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
  Route::post('/users/create', [UserController::class, 'store']);
  Route::get('/users/{id}', [UserController::class, 'update'])->name('users.update');
  Route::put('/users/{id}', [UserController::class, 'edit']);
  Route::delete('/users/{id}', [UserController::class, 'delete']);

  Route::get('/clients', [ClientController::class, 'index'])->name('clients');
  Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
  Route::post('/clients/create', [ClientController::class, 'store']);
  Route::get('/clients/{id}', [ClientController::class, 'update'])->name('clients.update');
  Route::put('/clients/{id}', [ClientController::class, 'edit']);
  Route::delete('/clients/{id}', [ClientController::class, 'delete']);

  Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers');
  Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
  Route::post('/suppliers/create', [SupplierController::class, 'store']);
  Route::get('/suppliers/{id}', [SupplierController::class, 'update'])->name('suppliers.update');
  Route::put('/suppliers/{id}', [SupplierController::class, 'edit']);
  Route::delete('/suppliers/{id}', [SupplierController::class, 'delete']);
  
  Route::get('/inventory', [SupplierController::class, 'index'])->name('inventory');

  Route::get('/orders', [OrderController::class, 'index'])->name('orders');
});

require __DIR__.'/auth.php';
