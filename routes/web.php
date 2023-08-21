<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Orderentry\OrderentryController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Supply\SupplyController;

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
  Route::get('/users/{id}/edit', [UserController::class, 'update'])->name('users.update');
  Route::put('/users/{id}/edit', [UserController::class, 'edit']);
  Route::delete('/users/{id}', [UserController::class, 'delete']);

  Route::get('/clients', [ClientController::class, 'index'])->name('clients');
  Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
  Route::post('/clients/create', [ClientController::class, 'store']);
  Route::get('/clients/{id}/edit', [ClientController::class, 'update'])->name('clients.update');
  Route::put('/clients/{id}/edit', [ClientController::class, 'edit']);
  Route::delete('/clients/{id}', [ClientController::class, 'delete']);

  Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers');
  Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
  Route::post('/suppliers/create', [SupplierController::class, 'store']);
  Route::get('/suppliers/{id}/edit', [SupplierController::class, 'update'])->name('suppliers.update');
  Route::put('/suppliers/{id}/edit', [SupplierController::class, 'edit']);
  Route::delete('/suppliers/{id}', [SupplierController::class, 'delete']);
  
  Route::get('/inventory', [SupplyController::class, 'index'])->name('inventory');

  Route::get('/orders', [OrderentryController::class, 'index'])->name('orders');
  Route::get('/orders/{id}', [OrderentryController::class, 'view'])->name('orders.view');
  Route::get('/orders/create', [OrderentryController::class, 'create'])->name('orders.create');
  Route::post('/orders/create', [OrderentryController::class, 'store']);
  Route::get('/orders/{oid}/add', [SupplyController::class, 'create'])->name('orders.add');
  Route::post('/orders/{oid}/add', [SupplyController::class, 'store']);
  Route::get('/orders/{id}/edit', [OrderentryController::class, 'update'])->name('orders.update');
  Route::put('/orders/{id}/edit', [OrderentryController::class, 'edit']);
  Route::get('/orders/{oid}/receive', [OrderentryController::class, 'receive'])->name('orders.receive');
  Route::post('/orders/{oid}/receive', [OrderentryController::class, 'pickup']);
  Route::post('/orders/filters', [OrderentryController::class, 'filters'])->name('orders.filters');
  Route::delete('/orders/{id}', [OrderentryController::class, 'delete']);

  Route::get('/supplies', [SupplyController::class, 'index'])->name('supplies');
  Route::get('/supplies/create', [SupplyController::class, 'create'])->name('supplies.create');
  Route::post('/supplies/create', [SupplyController::class, 'store']);
  Route::get('/supplies/{id}/edit', [SupplyController::class, 'update'])->name('supplies.update');
  Route::put('/supplies/{id}/edit', [SupplyController::class, 'edit']);
  Route::get('/supplies/{id}/edit', [SupplyController::class, 'receive'])->name('supplies.receive');
  Route::put('/supplies/{id}/edit', [SupplyController::class, 'pickup']);
  Route::delete('/supplies/{id}', [SupplyController::class, 'delete']);
});

require __DIR__.'/auth.php';
