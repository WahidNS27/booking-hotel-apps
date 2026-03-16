<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ReservationController;




Route::get('/', [LoginController::class,'index']);
Route::post('/login', [LoginController::class,'store']);

   // Route dashboard - SESUAIKAN DENGAN YANG ANDA PAKAI
Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard/checklists', [App\Http\Controllers\LoginController::class, 'getCheckLists'])->name('dashboard.checklists');

Route::get('/logout', [LoginController::class,'logout']);

    // =========== GUEST RESOURCE ===========
    Route::resource('guests', GuestController::class);

     // =========== RESERVATION RESOURCE ===========
     Route::resource('reservations', ReservationController::class);
     Route::get('/reservations/{reservation}/pdf', [ReservationController::class, 'generatePdf'])->name('reservations.pdf');

