<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'web'])->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index']);
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', App\Http\Controllers\ProfileController::class)->name('profile');

    // Settings
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::resource('roles', App\Http\Controllers\RoleAndPermissionController::class);

    // Master Data
    Route::resource('factories', App\Http\Controllers\FactoryController::class);
    Route::resource('pakets', App\Http\Controllers\PaketController::class);
    Route::resource('clients', App\Http\Controllers\ClientController::class);
    Route::resource('mesins', App\Http\Controllers\MesinController::class);
    Route::resource('units', App\Http\Controllers\UnitController::class);

    // Pembelian Paket
    Route::resource('transaksi-pembelians', App\Http\Controllers\TransaksiPembelianController::class);
    Route::get('/transaksi-pembelians/{transaksiPembelian}/detail', [App\Http\Controllers\TransaksiPembelianController::class, 'detail'])->name('transaksi-pembelians.detail');
    Route::post('/transaksi-pembelians/{transaksiPembelian}/payment', [App\Http\Controllers\TransaksiPembelianController::class, 'payment'])->name('transaksi-pembelians.payment');
    Route::post('/transaksi-pembelians/{transaksiPembelian}/pay-item', [App\Http\Controllers\TransaksiPembelianController::class, 'payItem'])->name('transaksi-pembelians.pay-item');
    Route::get('/laporan-pembelian', [App\Http\Controllers\LaporanPembelianController::class, 'index'])->name('laporan-pembelian.index');
    Route::get('/laporan-pembelian/print', [App\Http\Controllers\LaporanPembelianController::class, 'print'])->name('laporan-pembelian.print');

    // Proses Mesin
    Route::resource('transaksi-mesins', App\Http\Controllers\TransaksiMesinController::class);
    Route::get('/transaksi-mesins/{transaksiMesin}/detail', [App\Http\Controllers\TransaksiMesinController::class, 'detail'])->name('transaksi-mesins.detail');
    Route::post('/transaksi-mesins/{transaksiMesin}/payment', [App\Http\Controllers\TransaksiMesinController::class, 'payment'])->name('transaksi-mesins.payment');
    Route::get('/ringkasan-mesin', [App\Http\Controllers\RingkasanMesinController::class, 'index'])->name('ringkasan-mesin.index');
    Route::get('/nota-pabrik', [App\Http\Controllers\NotaPabrikController::class, 'index'])->name('nota-pabrik.index');
    Route::post('/nota-pabrik/print', [App\Http\Controllers\NotaPabrikController::class, 'print'])->name('nota-pabrik.print');
    Route::get('/nota-penjualan', [App\Http\Controllers\NotaPenjualanController::class, 'index'])->name('nota-penjualan.index');
    Route::post('/nota-penjualan/print', [App\Http\Controllers\NotaPenjualanController::class, 'print'])->name('nota-penjualan.print');

    // File Manager
    Route::get('/nota-histories', [App\Http\Controllers\NotaHistoryController::class, 'index'])->name('nota-histories.index');
    Route::get('/nota-histories/{notaHistory}', [App\Http\Controllers\NotaHistoryController::class, 'show'])->name('nota-histories.show');
    Route::get('/nota-histories/{notaHistory}/regenerate', [App\Http\Controllers\NotaHistoryController::class, 'regenerate'])->name('nota-histories.regenerate');
    Route::delete('/nota-histories/{notaHistory}', [App\Http\Controllers\NotaHistoryController::class, 'destroy'])->name('nota-histories.destroy');
});
