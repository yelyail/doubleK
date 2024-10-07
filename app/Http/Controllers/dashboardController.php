<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tblproduct;
use App\Models\tblservice;

class dashboardController extends Controller
{
    public function order(){ 
        $products = tblproduct::all();
        $services = tblservice::all();
        $orderDetailsData = [];
        $overallTotal = 0;

        return view('user.order', compact('services', 'products'));
    }
    public function reports(){ 
        return view('user.reports');
    }
}
