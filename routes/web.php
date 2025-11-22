<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\ArchiveInvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoiceAttachmentsController;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


Route::resource('invoices', InvoicesController::class)->middleware('auth');
Route::resource('sections', SectionsController::class)->middleware('auth');
Route::resource('products', ProductsController::class)->middleware('auth');
Route::get('/section/{id}', [InvoicesController::class, 'getproducts'])->middleware('auth');
Route::get('/InvoicesDetails/{id}', [InvoicesDetailsController::class, 'index'])->middleware('auth');
Route::get('edit_invoice/{id}', [InvoicesController::class, 'edit'])->name('invoices.edit')->middleware('auth');

Route::get('/Status_show/{id}', [InvoicesController::class, 'show'])->middleware('auth')->name('Status_show');
Route::post('/Status_Update/{id}', [InvoicesController::class, 'Status_Update'])->name('Status_Update')->middleware('auth');
Route::post('/Invoice_Paid', [InvoicesController::class, 'Invoice_Paid'])->name('Invoice_Paid')->middleware('auth');
Route::post('/Invoice_UnPaid', [InvoicesController::class, 'Invoice_UnPaid'])->name('Invoice_UnPaid')->middleware('auth');
Route::post('/Invoice_Partial', [InvoicesController::class, 'Invoice_Partial'])->name('Invoice_Partial')->middleware('auth');
Route::resource('Archive', ArchiveInvoicesController::class)->middleware('auth');

Route::get('View_file/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'open_file'])->middleware('auth');
Route::get('download/{invoice_number}/{file_name}', [InvoicesDetailsController::class, 'get_file'])->middleware('auth');
Route::post('delete_file', [InvoicesDetailsController::class, 'destroy'])->name('delete_file')->middleware('auth');
Route::post('/InvoiceAttachments', [InvoiceAttachmentsController::class, 'store'])->name('InvoiceAttachments.store')->middleware('auth');

require __DIR__.'/auth.php';
Route::get('/{page}', [AdminController::class, 'index'])->name('index');


