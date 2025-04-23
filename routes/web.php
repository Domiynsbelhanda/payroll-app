<?php

use App\Http\Controllers\PayslipPdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/fiche-paie/{payslip}', PayslipPdfController::class)->name('fiche-paie.pdf');
