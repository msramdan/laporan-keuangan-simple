<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'web'])->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index']);
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', App\Http\Controllers\ProfileController::class)->name('profile');

    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('roles', App\Http\Controllers\RoleAndPermissionController::class);

    Route::resource('factories', App\Http\Controllers\FactoryController::class);
    Route::resource('products', App\Http\Controllers\ProductController::class);

    Route::resource('funds', App\Http\Controllers\FundController::class);
    Route::resource('purchases', App\Http\Controllers\PurchaseController::class);
    Route::resource('sales', App\Http\Controllers\SaleController::class);
    Route::resource('units', App\Http\Controllers\UnitController::class);

    Route::get('/reports/print', [App\Http\Controllers\ReportController::class, 'print'])->name('reports.print');
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
});

Route::middleware(['auth', 'permission:test view'])->get('/tests', function () {
    dd('This is just a test and an example for permission and sidebar menu. You can remove this line on web.php, config/permission.php and config/generator.php');
})->name('tests.index');
