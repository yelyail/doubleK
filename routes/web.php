<?php

use App\Http\Controllers\adminaccess;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\orderReceipt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\receiptPrintController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\salesReceiptController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('signin')->middleware('checkLogin');
// Authentication Routes
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('adminDashboard');
Route::get('/user/dashboard', [HomeController::class, 'userDashboard'])->name('userDashboard');

Route::post('/loginSave', [AuthController::class, 'loginSave'])->name('loginSave')->middleware('checkLogin');
Route::get('/logout', [AuthController::class, 'logout'])->name('signout');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/registerStore', [AuthController::class, 'registerSave'])->name('registerSave');


// Admin Routes
Route::middleware(['auth','userAccess:0'])->prefix('admin')->group(function() {
    Route::controller(adminaccess::class)->group(function() {
        Route::get('/employee', 'adminEmployee')->name('adminEmployee'); 
        Route::get('/inventory', 'adminInventory')->name('adminInventory');
        Route::get('/order', 'adminOrder')->name('adminOrder');
        Route::get('/inventoryReports', 'adminInventoryReports')->name('adminInventoryReports');
        Route::get('/salesReport', 'adminSalesReport')->name('adminSalesReport');
        Route::get('/service', 'adminService')->name('adminService');
        Route::get('/supplier', 'adminSupplier')->name('adminSupplier');
        Route::get('/custInfo', 'adminCustInfo')->name('adminCustInfo');
        Route::get('/confirm', 'adminConfirm')->name('adminConfirm');
    // Edit and Update Routes
        Route::get('/employee/{id}/edit', 'editClient')->name('editEmployee');
        Route::post('/employee/{id}/archive', 'archiveClient')->name('archiveClient');
        Route::post('/employee/{id}/update', 'updateClient')->name('updateClient');
        
        Route::get('/supplier/{id}/edit', 'editSupplier')->name('editSupplier');
        Route::post('/supplier/{id}/archive', 'archiveSupplier')->name('archiveSupplier');
        Route::post('/supplier/{id}/update', 'updateSupplier')->name('updateSupplier');

        Route::get('/service/{id}/editServices', 'editService')->name('editService');
        Route::post('/service/{id}/update', 'updateService')->name('updateService'); 
        Route::post('/service/{serviceID}/archive', 'archiveService')->name('archiveService');
        Route::get('/service/{serviceID}/activateService', 'activateService')->name('activateService');
    });
    // For posting
    Route::prefix('/inventory')->group(function() {
        Route::get('{productID}/edit', [adminaccess::class, 'editProduct'])->name('editProduct');
        Route::post('{productID}/archive', [adminaccess::class, 'archiveInventory'])->name('archiveInventory');
        Route::post('{productID}/update', [adminaccess::class, 'updateInventory'])->name('updateInventory');
    });

    Route::controller(adminaccess::class)->group(function(){
        Route::post('/storeProduct', 'storeProduct')->name('storeProduct');
        Route::post('/storeClient', 'storeClient')->name('storeClient');
        Route::post('/storeCustomer', 'storeCustomer')->name('storeCustomer');
        Route::post('/storeService', 'storeService')->name('storeService');
        Route::post('/storeSupplier', 'storeSupplier')->name('storeSupplier');
        Route::post('/storeCustomerInfor', 'storeCustomerInfor')->name('storeCustomerInfor');
        Route::post('/addProduct', 'addProduct')->name('addProduct');
        Route::post('/addService', 'addService')->name('addService');
    });
});

// for order
Route::middleware(['auth','userAccess:0,1,2'])->group(function() {
    Route::controller(orderReceipt::class)->group(function() {
        
        Route::post('/admin/confirm/storeReservation', 'storeReservation')->name('storeReservation');
        Route::post('/user/confirm/storeReservation', 'storeReservation')->name('storeReservation1');
    });
});

// User Routes
Route::middleware(['auth'])->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/user/order', 'userOrder')->name('userOrder');
        Route::get('/user/reports', 'userReports')->name('userReports');
    });
});

// Printing Receipt Routes
Route::post('/confirmPayment', [orderReceipt::class, 'confirmPayment'])->middleware('userAccess:0,1,2');
Route::post('/cancel/{creditID}', [orderReceipt::class, 'cancel'])->name('orderCancel')->middleware('userAccess:0,1,2');
Route::post('/storeCredit',[orderReceipt::class, 'storeCredit'])->name('storeCredit')->middleware('userAccess:0,1,2');
Route::get('/receipt/{ordDet_ID}', [OrderReceipt::class, 'generatePdf'])->name('generateReceipt')->middleware('userAccess:0,1,2');
Route::get('/receipt', [OrderReceipt::class, 'tempReceipt']);
Route::post('/admin/return', [salesReceiptController::class, 'requestRepair'])->name('requestRepair')->middleware('userAccess:0,1,2');
Route::get('/salesReport', [salesReceiptController::class, 'salesReceipt'])->name('generateSalesReport')->middleware('userAccess:0,1,2');
Route::post('/confirm/storeOrderReceipt',[orderReceipt::class, 'storeReceipt'])->name('storeReceipt')->middleware('userAccess:0,1,2');
Route::get('/inventoryReceipt', [salesReceiptController::class, 'inventoryReceipt'])->name('generateInventoryReports')->middleware('userAccess:0,1,2');
Route::post('/confirmRepair/{ordDet_ID}', [salesReceiptController::class, 'updateStatus'])->name('updateStatus')->middleware('userAccess:0,1,2');

//require __DIR__.'/auth.php';
