<?php

use App\Http\Controllers\adminaccess;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\orderReceipt;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\receiptPrintController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
})->middleware('checkLogin');

// Authentication Routes
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::middleware('userAccess:0')->get('/admin/dashboard', [HomeController::class, 'adminDashboard'])->name('adminDashboard');
Route::middleware('userAccess:1,2')->get('/user/dashboard', [HomeController::class, 'userDashboard'])->name('userDashboard');

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('checkLogin');
Route::post('/loginStore', [AuthController::class, 'loginSave'])->name('loginSave')->middleware('checkLogin');
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
        Route::post('/admin/confirm/storeOrderReceipt', 'storeReceipt')->name('storeReceipt');
        Route::post('/admin/confirm/storeReservation', 'storeReservation')->name('storeReservation');
        Route::post('/user/confirm/storeReceipt', 'storeReceipt')->name('storeReceipt1');
        Route::post('/user/confirm/storeReservation', 'storeReservation')->name('storeReservation1');
    });
});

// User Routes
Route::middleware(['auth','userAccess:1,2'])->group(function() {
    Route::controller(dashboardController::class)->group(function() {
        Route::get('/user/order', 'order')->name('userOrder');
        Route::get('/user/reports', 'reports')->name('userReports');
    });
});

// Printing Receipt Routes
Route::get('orderReceipt', [receiptPrintController::class, 'orderReceipt']);
Route::get('receipt', [receiptPrintController::class, 'receipt']);

//require __DIR__.'/auth.php';
