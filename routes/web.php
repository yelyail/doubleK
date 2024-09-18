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
Route::controller(adminaccess::class)->group(function(){
    Route::get('/admin/dashboard','adminDashboard')->name('adminDashboard');
    Route::get('/admin/inventory','adminInventory')->name('adminInventory');
    Route::get('/admin/order','adminOrder')->name('adminOrder');
    Route::get('/admin/inventoryReports','adminInventoryReports')->name('adminInventoryReports');
    Route::get('/admin/salesReport','adminSalesReport')->name('adminSalesReport');
    Route::get('/admin/reservation','adminReservation')->name('adminReservation');
    Route::get('/admin/service','adminService')->name('adminService');
    Route::get('/admin/supplier','adminSupplier')->name('adminSupplier');
});

//for the user
Route::controller(dashboardController::class)->group(function() {
    Route::get('/user/dashboard','dashboard')->name('userDashboard');
    Route::get('/user/order','order')->name('userOrder');
    Route::get('/user/reservation','reservation')->name('userReservation');
    Route::get('/user/service','service')->name('userService');
});
//require __DIR__.'/auth.php';
