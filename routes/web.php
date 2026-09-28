<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MyBookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/home');

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/booking/{room:slug}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}/confirmation', [BookingController::class, 'confirmation'])->name('booking.confirmation');

    Route::get('/payment/{booking}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{booking}', [PaymentController::class, 'process'])->name('payment.process');

    Route::get('/my-bookings', [MyBookingController::class, 'index'])->name('my-bookings.index');
    Route::get('/my-bookings/{booking}/edit', [MyBookingController::class, 'edit'])->name('my-bookings.edit');
    Route::put('/my-bookings/{booking}', [MyBookingController::class, 'update'])->name('my-bookings.update');
    Route::post('/my-bookings/{booking}/cancel', [MyBookingController::class, 'cancel'])->name('my-bookings.cancel');
    Route::delete('/my-bookings/{booking}', [MyBookingController::class, 'destroy'])->name('my-bookings.destroy');

    Route::resource('rooms', RoomController::class)->except('show');

    Route::get('/laporan-pemesanan', [BookingReportController::class, 'index'])->name('laporan-pemesanan.index');
    Route::get('/laporan-pemesanan/export-pdf', [BookingReportController::class, 'exportPdf'])->name('laporan-pemesanan.export-pdf');
});

require __DIR__.'/auth.php';
