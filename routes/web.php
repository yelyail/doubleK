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
    return view('welcome');
});

Route::controller(AuthController::class)->group(function() {
    Route::get('/register', 'register')->name('register');
    Route::post('/registerStore', 'registerSave')->name('register.save');
    Route::get('/login', 'login')->name('login');
    Route::post('/loginStore', 'loginSave')->name('login.action');
});

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

Route::controller(dashboardController::class)->group(function() {
    Route::get('/user/dashboard','userDashboard')->name('userDashboard');
    Route::get('/user/order','userOrder')->name('userOrder');
    Route::get('/user/reservation','userReservation')->name('userReservation');
    Route::get('/user/service','userService')->name('userService');
});
require __DIR__.'/auth.php';
