<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\FasilitasController as AdminFasilitasController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\JenisOlahragaController as AdminJenisOlahragaController;
use App\Http\Controllers\Admin\LapanganController as AdminLapanganController;
use App\Http\Controllers\Customer\HomeController as CustomerHomeController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [CustomerHomeController::class, 'index'])->name('home');

Route::post('/midtrans/notification', [CustomerPaymentController::class, 'notification'])
    ->withoutMiddleware([
        \App\Http\Middleware\VerifyCsrfToken::class,
    ])
    ->name('midtrans.notification');

Route::get('/dashboard1', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/booking', [AdminBookingController::class, 'index'])
        ->name('booking.index');
    Route::patch('/booking/{booking}/confirm', [AdminBookingController::class, 'confirm'])
        ->name('booking.confirm');
    Route::patch('/booking/{booking}/cancel', [AdminBookingController::class, 'cancel'])
        ->name('booking.cancel');
    Route::patch('/booking/{booking}/complete', [AdminBookingController::class, 'complete'])
        ->name('booking.complete');

    Route::get('/pembayaran', [AdminPembayaranController::class, 'index'])
        ->name('pembayaran.index');

    Route::resource('lapangan', AdminLapanganController::class)->except(['show']);
    Route::resource('jenis-olahraga', AdminJenisOlahragaController::class)->except(['show']);
    Route::resource('fasilitas', AdminFasilitasController::class)->except(['show']);

});

Route::middleware(['auth', 'role:customer'])->group(function () {

    

    Route::get('/booking/{lapangan}', [CustomerBookingController::class, 'create'])
        ->name('customer.bookings.create');

    Route::post('/booking/{lapangan}', [CustomerBookingController::class, 'store'])
        ->name('customer.bookings.store');

    Route::get('/pembayaran/{booking}', [CustomerPaymentController::class, 'show'])
        ->name('customer.payments.show');

});

require __DIR__.'/auth.php';
