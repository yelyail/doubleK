<?php

namespace App\Http\Controllers;
use App\Models\tblproduct;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\tblorderitems;
use App\Models\tblservice;
use App\Models\tblorderreceipt;

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
        return view('auth.login');
    } 
    public function adminDashboard()
    {
        return $this->dashboardView('admin.dashboard');
    }
    public function userDashboard()
    {
        Log::info('Inside userDashboard controller');
        return $this->dashboardView('user.dashboard');
    }
    private function dashboardView($view, $data = [])
    {
        $products = tblproduct::with('inventory')->get();

        $totalStock = 0;
        $lowStockItems = 0; 
        $outOfStockItems = 0; 
        $lowStockProducts = []; 
        $outOfStockProducts = []; 

        foreach ($products as $product) {
            $inventoryItem = $product->inventory;
            if ($inventoryItem) {
                $totalStock += $inventoryItem->stock_qty;
                if ($inventoryItem->stock_qty < 5) {
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

        // Get customer order details for display
        $customerOrders = tblorderreceipt::with(['orderItems.product', 'customer', 'credit'])
            ->whereHas('credit', function($query) {
                $query->where('credit_status', 'active');
            })
            ->get()
            ->map(function ($orderReceipt) {
                return [
                    'customer_name' => $orderReceipt->customer->customer_name ?? 'N/A', 
                    'product_name' => $orderReceipt->orderItems->map(fn($item) => $item->product->product_name ?? 'N/A')->join(', '), // Join product names if multiple
                    'total_price' => $orderReceipt->orderItems->sum('total_price'), 
                    'reserve_date' => $orderReceipt->order_date,
                    'type' => $orderReceipt->credit->credit_type ?? 'N/A',
                ];
            });




        return view($view, array_merge(compact(
            'totalStock', 
            'lowStockItems', 
            'outOfStockItems', 
            'lowStockProducts', 
            'outOfStockProducts',
            'bestSellers',
            'products',
            'customerOrders' // Add this line to pass the orders data to the view
        ), $data));
    }
    }
