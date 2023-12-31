<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Orderentry\OrderentryController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Supplier\SupplierController;
use App\Http\Controllers\Supply\SupplyController;
use App\Http\Controllers\Work\DeliveryController;
use App\Http\Controllers\Work\ProductController;
use App\Http\Controllers\Work\WorkController;
use App\Http\Controllers\Work\ManufacturerController;

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

  Route::get('/profile', [UserController::class, 'profile'])->name('profile');
  Route::get('/profile/password', [UserController::class, 'password'])->name('profile.password');
  Route::post('/profile/password', [UserController::class, 'password_save']);

  Route::get('/users', [UserController::class, 'index'])->name('users');
  Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
  Route::post('/users/create', [UserController::class, 'store']);
  Route::get('/users/{id}/edit', [UserController::class, 'update'])->name('users.update');
  Route::put('/users/{id}/edit', [UserController::class, 'edit']);
  Route::delete('/users/{id}', [UserController::class, 'delete'])->name('users.delete');

  Route::get('/clients', [ClientController::class, 'index'])->name('clients');
  Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
  Route::post('/clients/create', [ClientController::class, 'store']);
  Route::get('/clients/{id}/edit', [ClientController::class, 'update'])->name('clients.update');
  Route::put('/clients/{id}/edit', [ClientController::class, 'edit']);
  Route::delete('/clients/{id}', [ClientController::class, 'delete']);
  Route::post('/clients/search', [ClientController::class, 'search'])->name('clients.search');

  Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers');
  Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
  Route::post('/suppliers/create', [SupplierController::class, 'store']);
  Route::get('/suppliers/{id}/edit', [SupplierController::class, 'update'])->name('suppliers.update');
  Route::put('/suppliers/{id}/edit', [SupplierController::class, 'edit']);
  Route::delete('/suppliers/{id}', [SupplierController::class, 'delete']);
  
  Route::get('/inventory', [SupplyController::class, 'index'])->name('inventory');
  Route::get('/inventory/entries', [SupplyController::class, 'entries'])->name('inventory.entries');
  Route::get('/inventory/deliveries', [SupplyController::class, 'deliveries'])->name('inventory.deliveries');

  Route::get('/orders', [OrderentryController::class, 'index'])->name('orders');
  Route::get('/orders/{id}/view', [OrderentryController::class, 'view'])->name('orders.view');
  Route::get('/orders/create', [OrderentryController::class, 'create'])->name('orders.create');
  Route::post('/orders/create', [OrderentryController::class, 'store']);
  Route::get('/orders/{oid}/add', [SupplyController::class, 'create'])->name('orders.add');
  Route::post('/orders/{oid}/add', [SupplyController::class, 'store']);
  Route::get('/orders/{id}/edit', [OrderentryController::class, 'update'])->name('orders.update');
  Route::put('/orders/{id}/edit', [OrderentryController::class, 'edit']);
  Route::get('/orders/{id}/list', [OrderentryController::class, 'list'])->name('orders.list');
  Route::get('/orders/{oid}/receive', [OrderentryController::class, 'receive'])->name('orders.receive');
  Route::post('/orders/{oid}/receive', [OrderentryController::class, 'pickup']);
  Route::post('/orders/filters', [OrderentryController::class, 'filters'])->name('orders.filters');
  Route::delete('/orders/{id}', [OrderentryController::class, 'delete']);

  Route::get('/supplies', [SupplyController::class, 'index'])->name('supplies');
  Route::get('/supplies/create', [SupplyController::class, 'create'])->name('supplies.create');
  Route::post('/supplies/create', [SupplyController::class, 'store']);
  Route::get('/supplies/{id}/edit', [SupplyController::class, 'update'])->name('supplies.update');
  Route::put('/supplies/{id}/edit', [SupplyController::class, 'edit']);
  Route::get('/supplies/{id}/pickup', [SupplyController::class, 'receive'])->name('supplies.receive');
  Route::put('/supplies/{id}/pickup', [SupplyController::class, 'pickup']);
  Route::delete('/supplies/{id}', [SupplyController::class, 'delete']);
  Route::post('/supplies/search', [SupplyController::class, 'search'])->name('supplies.search');
  
  Route::get('/work-progress', [WorkController::class, 'index'])->name('work-progress');
  Route::get('/works/create', [WorkController::class, 'create'])->name('works.create');
  Route::post('/works/create', [WorkController::class, 'store']);
  Route::get('/works/{id}/view', [WorkController::class, 'view'])->name('works.view');
  Route::get('/works/{id}/edit', [WorkController::class, 'update'])->name('works.update');
  Route::put('/works/{id}/edit', [WorkController::class, 'edit']);
  Route::get('/works/{id}/list', [WorkController::class, 'list'])->name('works.list');
  Route::post('/works/filters', [WorkController::class, 'filters'])->name('works.filters');
  Route::delete('/works/{id}', [WorkController::class, 'delete']);
  Route::get('/works/{wid}/add', [ProductController::class, 'create'])->name('works.add');
  Route::post('/works/{wid}/add', [ProductController::class, 'store']);
  Route::get('/works/{wid}/starting-order', [WorkController::class, 'starting_order'])->name('works.starting_order');
  Route::post('/works/{wid}/starting-order', [WorkController::class, 'starting_order_save']);
  

  Route::get('/deliveries', [DeliveryController::class, 'index'])->name('deliveries');
  Route::get('/works/{wid}/deliveries/create', [DeliveryController::class, 'create'])->name('works.deliveries.create');
  Route::post('/works/{wid}/deliveries/create', [DeliveryController::class, 'store']);
  Route::get('/works/{wid}/deliveries/{id}/edit', [DeliveryController::class, 'update'])->name('works.deliveries.update');
  Route::put('/works/{wid}/deliveries/{id}/edit', [DeliveryController::class, 'edit']);
  
  Route::get('/products', [ProductController::class, 'index'])->name('products');
  Route::get('/products/{id}/edit', [ProductController::class, 'update'])->name('products.update');
  Route::put('/products/{id}/edit', [ProductController::class, 'edit']);
  Route::delete('/products/{id}', [ProductController::class, 'delete'])->name('products.delete');
  Route::get('/products/{id}/photo/add', [ProductController::class, 'addPhoto'])->name('products.photo.add');
  Route::post('/products/{id}/photo/add', [ProductController::class, 'savePhoto']);

  Route::get('/manufacturers', [ManufacturerController::class, 'index'])->name('manufacturers');
  Route::get('/manufacturers/create', [ManufacturerController::class, 'create'])->name('manufacturers.create');
  Route::post('/manufacturers/create', [ManufacturerController::class, 'store']);
  Route::get('/manufacturers/{id}/edit', [ManufacturerController::class, 'update'])->name('manufacturers.update');
  Route::put('/manufacturers/{id}/edit', [ManufacturerController::class, 'edit']);
  Route::delete('/manufacturers/{id}', [ManufacturerController::class, 'delete']);
  Route::post('/manufacturers/search', [ManufacturerController::class, 'search'])->name('manufacturers.search');

  Route::get('/reports', [ReportController::class, 'main'])->name('reports');
  Route::get('/reports/material-stock', [ReportController::class, 'raw_material_stock'])->name('reports.raw_material_stock');
  Route::post('/reports/material-stock', [ReportController::class, 'raw_material_stock_post']);
  Route::get('/reports/material-stock/download', [ReportController::class, 'raw_material_stock_download'])->name('reports.raw_material_stock.download');
  Route::get('/reports/works-and-deadline', [ReportController::class, 'works_and_deadline'])->name('reports.works_and_deadline');
  Route::post('/reports/works-and-deadline', [ReportController::class, 'works_and_deadline_post']);
  Route::get('/reports/works-and-deadline/download', [ReportController::class, 'works_and_deadline_download'])->name('reports.works_and_deadline.download');
  Route::get('/reports/workshops-with-jobs', [ReportController::class, 'workshops_with_jobs'])->name('reports.workshops_with_jobs');
  Route::post('/reports/workshops-with-jobs', [ReportController::class, 'workshops_with_jobs_post']);
  Route::get('/reports/workshops-with-jobs/download', [ReportController::class, 'workshops_with_jobs_download'])->name('reports.workshops_with_jobs.download');
  Route::get('/reports/workshops-delivered-material', [ReportController::class, 'workshops_delivered_material'])->name('reports.workshops_delivered_material');
  Route::post('/reports/workshops-delivered-material', [ReportController::class, 'workshops_delivered_material_post']);
  Route::get('/reports/workshops-delivered-material/download', [ReportController::class, 'workshops_delivered_material_download'])->name('reports.workshops_delivered_material.download');
  Route::get('/reports/merchandise-clearance', [ReportController::class, 'merchandise_clearance'])->name('reports.merchandise_clearance');
  Route::post('/reports/merchandise-clearance', [ReportController::class, 'merchandise_clearance_post']);
  Route::get('/reports/merchandise-clearance/download', [ReportController::class, 'merchandise_clearance_download'])->name('reports.merchandise_clearance.download');
});

require __DIR__.'/auth.php';
