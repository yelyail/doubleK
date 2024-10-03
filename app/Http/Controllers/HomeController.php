<?php

namespace App\Http\Controllers;

use App\Models\tblinventory;
use App\Models\tblproduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\tblorderitems;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('/login');
    }
    public function adminDashboard()
    {
        return $this->dashboardView('admin.dashboard');
    }

    public function userDashboard()
    {
        return $this->dashboardView('user.dashboard');
    }

    private function dashboardView($view)
    {
        $products = tblproduct::with('inventory')->get();

        $totalStock = 0;
        $lowStockItems = 0; 
        $outOfStockItems = 0; 
        $lowStockProducts = []; 
        $outOfStockProducts = []; 

        foreach ($products as $product) {
            // Get associated inventory item
            $inventoryItem = $product->inventory;
            if ($inventoryItem) {
                $totalStock += $inventoryItem->stock_qty;
                if ($inventoryItem->stock_qty < 10) {
                    $lowStockItems++;
                    $lowStockProducts[] = $product->product_name;
                }
                if ($inventoryItem->stock_qty <= 0) {
                    $outOfStockItems++;
                    $outOfStockProducts[] = $product->product_name;
                }
            }
        }

        // Get best-selling products
        $bestSellers = tblorderitems::select('product_id', DB::raw('SUM(qty_order) as total_units_sold'))
            ->groupBy('product_id')
            ->orderBy('total_units_sold', 'DESC')
            ->take(3)
            ->get()
            ->map(function ($order) {
                $product = tblproduct::find($order->product_id);
                return [
                    'product_name' => $product ? $product->product_name : 'Unknown Product',
                    'units_sold' => $order->total_units_sold,
                    'revenue' => $product ? ($order->total_units_sold * $product->unit_price) : 0,
                ];
            });

        return view($view, compact(
            'products', 
            'totalStock', 
            'lowStockItems', 
            'outOfStockItems', 
            'lowStockProducts', 
            'outOfStockProducts',
            'bestSellers'
        ));
    }
}
