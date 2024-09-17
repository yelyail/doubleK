<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class adminaccess extends Controller
{
    public function adminDashboard(){ 
        return view('admin.dashboard');
    }
    public function adminInventory(){ 
        return view('admin.inventory');
    }
    public function adminOrder(){ 
        return view('admin.order');
    }
    public function adminInventoryReports(){ 
        return view('admin.reports');
    }
    public function adminSalesReport(){ 
        return view('admin.salesReport');
    }
    public function adminReservation(){ 
        return view('admin.reservation');
    }
    public function adminService(){ 
        return view('admin.service');
    }
    public function adminSupplier(){ 
        return view('admin.supplier');
    }
}
