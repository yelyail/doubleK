<?php

namespace App\Http\Controllers;

use App\Models\tblinventory;
use Illuminate\Http\Request;

class adminaccess extends Controller
{
    public function adminDashboard(){ 
        return view('admin.dashboard');
    }
    public function adminEmployee(){
        return view('admin.client');
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

    //for posting 
    public function storeProduct(Request $request){
        $inventory = new tblinventory;
        $inventory->name = $request->name;
        $inventory->description = $request->description;
        $inventory->price = $request->price;
        $inventory->quantity = $request->quantity;
        $inventory->save();
        return redirect()->back();
    }


    //for the progress \
    public function custInfo(){
        return view('admin.custInfo');
    }
    public function payMeth(){
        return view('admin.payMeth');
    }
    public function confirm(){
        return view('admin.confirm');
    }


}
