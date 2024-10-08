<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tblproduct;
use App\Models\tblservice;
use Illuminate\Support\Facades\DB;

class dashboardController extends Controller
{
    public function userOrder(){ 
        $products = tblproduct::all();
        $services = tblservice::all();
        $orderDetailsData = [];
        $overallTotal = 0;

        return view('user.order', compact('services', 'products'));
    }
    public function userReports(){ 
        $products = tblproduct::select(
            'tblproduct.product_id',
            'tblproduct.product_name',
            'tblproduct.categoryName',
            'tblsupplier.supplier_name',
            'tblinventory.stock_qty',
            DB::raw('SUM(tblorderitems.qty_order) AS total_qty_sold'),
            'tblproduct.unit_price',
            'tblproduct.prod_add',
            'tblinventory.nextRestockDate',
            'tblreturn.returnDate',
            'tblreturn.returnReason'
        )
        ->join('tblinventory', 'tblproduct.inventory_ID', '=', 'tblinventory.inventory_ID')
        ->join('tblsupplier', 'tblinventory.supplier_ID', '=', 'tblsupplier.supplier_ID')
        ->leftJoin('tblorderitems', 'tblproduct.product_id', '=', 'tblorderitems.product_id')
        ->leftJoin('tblreturn', 'tblproduct.product_id', '=', 'tblreturn.product_id')
        ->where('tblproduct.archived', false)
        ->groupBy(
            'tblproduct.product_id', 
            'tblproduct.product_name',
            'tblproduct.categoryName',
            'tblsupplier.supplier_name',
            'tblinventory.stock_qty',
            'tblproduct.unit_price',
            'tblinventory.nextRestockDate',
            'tblreturn.returnDate',
            'tblreturn.returnReason'
        )
        ->get();

        return view('user.reports',compact('products'));
    }
}
