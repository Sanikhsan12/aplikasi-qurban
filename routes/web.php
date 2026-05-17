<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DanaDKMController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PelaksanaanController;
use App\Http\Controllers\PenyembelihanController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DanaOperasionalController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\KetersediaanHewanController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

// Public payment routes
Route::post('payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
Route::get('payment/error', [PaymentController::class, 'error'])->name('payment.error');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// routes/web.php
Route::middleware(['auth', 'role:peserta_kurban'])
    ->prefix('peserta')
    ->name('peserta.')
    ->group(function () {

        // Dashboard peserta
        Route::get('dashboard', [UserDashboardController::class, 'index'])
            ->name('dashboard');

        // Order resource
        Route::resource('order', OrderController::class);

        // Payment routes
        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('order/{order}', [PaymentController::class, 'show'])->name('show');
            Route::get('order/{order}/snap-token', [PaymentController::class, 'getSnapToken'])->name('get-snap-token');
            Route::get('verify/{order}', [PaymentController::class, 'verify'])->name('verify');
        });
        // Optional: custom routes
        Route::get('order/{order}/invoice', [OrderController::class, 'invoice'])
            ->name('order.invoice');
    });

Route::middleware(['auth', 'role:admin_kurban'])->group(
    function () {
        Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin/dashboard');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('pelaksanaan', PelaksanaanController::class);
            Route::resource('ketersediaan-hewan', KetersediaanHewanController::class);
            Route::get('ketersediaan-hewan/export', [KetersediaanHewanController::class, 'export'])->name('ketersediaan-hewan.export');


            Route::resource('order', OrderController::class);
            Route::put('orders/{order}/verify', [OrderController::class, 'verify'])->name('orders.verify');
            Route::put('orders/{order}/reject', [OrderController::class, 'reject'])->name('orders.reject');
            Route::resource('penyembelihan', PenyembelihanController::class);
            Route::resource('dana-dkm', DanaDKMController::class);
            Route::resource('dana-operasional', DanaOperasionalController::class);
            Route::resource('users', UsersController::class);
            Route::resource('distribusi', DistribusiController::class);
            Route::resource('bank-penerima', BankController::class);

            Route::prefix('orders')->group(function () {
                Route::get('/verifikasi', [OrderController::class, 'verifikasi'])->name('orders.verifikasi');
                Route::get('/approved', [OrderController::class, 'approved'])->name('orders.approved');
                Route::get('/rejected', [OrderController::class, 'rejected'])->name('orders.rejected');
            });

            Route::prefix('distributions')->group(function () {
                Route::get('/perkiraan-penerima', [DistribusiController::class, 'perkiraanPenerima'])->name('distributions.perkiraan-penerima');
            });


            Route::prefix('penyembelihans')->group(function () {
                Route::get('/menunggu-penyembelihan', [PenyembelihanController::class, 'waiting'])->name('penyembelihans.menunggu-penyembelihan');
                Route::get('/tersembelih', [PenyembelihanController::class, 'tersembelih'])->name('penyembelihans.tersembelih');
            });
        });
    }
);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
