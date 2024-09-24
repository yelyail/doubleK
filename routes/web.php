<?php

use App\Http\Controllers\adminaccess;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('login');
});

Route::controller(AuthController::class)->group(function() {
    Route::get('/register', 'register')->name('register');
    Route::post('/registerStore', 'registerSave')->name('register.save');
    Route::get('/login', 'login')->name('login');
    Route::post('/loginStore', 'loginSave')->name('login.action');
    Route::get('/logout', 'logout')->name('logout');
});

//for the admin
//for getting
Route::controller(adminaccess::class)->group(function(){
    Route::get('/admin/dashboard','adminDashboard')->name('adminDashboard');
    Route::get('/admin/employee','adminEmployee')->name('adminEmployee');
    Route::get('/admin/inventory','adminInventory')->name('adminInventory');
    Route::get('/admin/order','adminOrder')->name('adminOrder');
    Route::get('/admin/inventoryReports','adminInventoryReports')->name('adminInventoryReports');
    Route::get('/admin/salesReport','adminSalesReport')->name('adminSalesReport');
    Route::get('/admin/reservation','adminReservation')->name('adminReservation');
    Route::get('/admin/service','adminService')->name('adminService');
    Route::get('/admin/supplier','adminSupplier')->name('adminSupplier');
    Route::get('/admin/custInfo','custInfo')->name('custInfo');
    Route::get('/admin/confirm','confirm')->name('confirm');

    Route::get('/admin/employee/{id}/edit', 'editClient')->name('editEmployee');
    Route::post('/admin/employee/{id}/archive', 'archiveClient')->name('archiveClient');
    Route::post('/admin/employee/{id}/update', 'updateClient')->name('updateClient');

    Route::get('/admin/supplier/{id}/edit', 'editSupplier')->name('editSupplier');
    Route::post('/admin/supplier/{id}/archive', 'archiveSupplier')->name('archiveSupplier');
    Route::post('/admin/supplier/{id}/update', 'updateSupplier')->name('updateSupplier');

    Route::get('/admin/service/{id}/editServices', 'editService')->name('editService');
    Route::post('/admin/service/{id}/update', 'updateService')->name('updateService');

    Route::post('/admin/inventory/{id}/archive', 'archiveInventory')->name('archiveInventory');
    Route::get('/admin/inventory/{product_id}/editInventory',  'editInventory')->name('editInventory');
    Route::post('/admin/inventory', 'updateInventory')->name('updateInventory');

});
//for posting
Route::controller(adminaccess::class)->group(function(){
    Route::post('/storeProduct','storeProduct')->name('storeProduct');
    Route::post('/storeClient','storeClient')->name('storeClient');
    Route::post('/storeCustomer','storeCustomer')->name('storeCustomer');
    Route::post('/storeService','storeService')->name('storeService');
    Route::post('/storeSupplier','storeSupplier')->name('storeSupplier');

    Route::post('/addProduct','addProduct')->name('addProduct');
});

//for the user
Route::controller(dashboardController::class)->group(function() {
    Route::get('/user/dashboard','dashboard')->name('userDashboard');
    Route::get('/user/order','order')->name('userOrder');
    Route::get('/user/reservation','reservation')->name('userReservation');
    Route::get('/user/service','service')->name('userService');
});
//require __DIR__.'/auth.php';
