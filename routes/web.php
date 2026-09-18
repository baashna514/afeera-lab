<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\LabTestController;
use App\Http\Controllers\Admin\PathologistReviewController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ResultEntryController;
use App\Http\Controllers\Admin\SampleCollectionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\FetchPaymentsController;
use App\Http\Controllers\Owner\CompanyController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'owner') {
        return redirect()->route('owner.dashboard');
    }
    if ($user->role === 'super_admin') {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// SaaS Owner Routes
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::resource('companies', CompanyController::class);
});

// Company Super Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('tests', LabTestController::class);

    // Patient Bookings
    Route::resource('bookings', BookingController::class)->except(['show', 'edit', 'update', 'destroy']);

    // Sample Collection & Barcodes
    Route::get('samples', SampleCollectionController::class.'@index')->name('samples.index');
    Route::post('samples/{item}/collect', SampleCollectionController::class.'@collect')->name('samples.collect');
    Route::get('samples/{item}/barcode', SampleCollectionController::class.'@printBarcode')->name('samples.barcode');

    // Result Entry
    Route::get('bookings/{booking}/results', [ResultEntryController::class, 'edit'])->name('results.edit');
    Route::put('bookings/{booking}/results', [ResultEntryController::class, 'update'])->name('results.update');

    // Pathologist Review & Final Reports
    Route::get('reviews', PathologistReviewController::class.'@index')->name('reviews.index');
    Route::get('reviews/{booking}', PathologistReviewController::class.'@show')->name('reviews.show');
    Route::post('reviews/{booking}/approve', PathologistReviewController::class.'@approve')->name('reviews.approve');
    Route::get('reviews/{booking}/report', PathologistReviewController::class.'@printReport')->name('reviews.report');

    // Earnings Report
    Route::get('reports/earnings', [ReportController::class, 'earnings'])->name('reports.earnings');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'store'])->name('settings.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Fetch payments endpoint (JSON) - accessible directly in browser
Route::get('/fetch-payments', FetchPaymentsController::class)->name('fetch-payments');
Route::get('/api/fetch-payments', FetchPaymentsController::class)->name('api.fetch-payments');

require __DIR__.'/auth.php';
