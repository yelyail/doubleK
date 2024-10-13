<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tblproduct;
use App\Models\tblservice;
use App\Models\tblcredit;
use App\Models\tblcustomer;
use App\Models\tblorderitems;
use App\Models\tblorderreceipt;
use App\Models\tblpaymentmethod;
use Illuminate\Support\Facades\DB;

class dashboardController extends Controller
{
    public function userOrder(Request $request)
    {
        $products = tblproduct::all();
        $services = tblservice::all();
        $credits = tblcredit::all();
        
        $customers = tblcustomer::with(['orderReceipts' => function ($query) {
            $query->with(['paymentMethod', 'credit']);
        }])->get();

        $orderDetailsData = [];
        $overallTotal = 0;

        if ($request->has('orderItems')) {
            foreach ($request->validatedData['orderItems'] as $item) {
                $productId = $item['type'] === 'product' ? $item['id'] : null;
                $serviceId = $item['type'] === 'service' ? $item['id'] : null;

                $orderItem = tblorderitems::create([
                    'product_id' => $productId,
                    'service_ID' => $serviceId,
                    'qty_order' => $item['quantity'],
                    'total_price' => $item['total'],
                ]);
                $orderItemsIds[] = $orderItem->orderitems_id;

                if ($item['type'] === 'product') {
                    $productsToUpdate[] = [
                        'id' => $item['id'],
                        'quantity' => $item['quantity'],
                    ];
                }
            }
        }

        foreach ($customers as $customer) {
            foreach ($customer->orderReceipts as $order) {
                if ($order->credit && $order->credit->creditID) {
                    foreach ($order->orderItems as $item) {
                        $totalPrice = $item->total_price; 
                        $initialPayment = $order->paymentMethod ? $order->paymentMethod->payment : 0;
                        $remainingBalance = $totalPrice - $initialPayment;

                        $paymentType = $order->paymentMethod ? $order->paymentMethod->payment_type : 'N/A';

                        $orderDetailsData[] = [
                            'creditID' => $order->credit->creditID,
                            'customer_name' => $customer->customer_name,
                            'particulars' => $item->product ? $item->product->product_name : 'N/A',
                            'quantity' => $item->qty_order,
                            'price' => $item->product ? $item->product->unit_price : 0,
                            'total_price' => $totalPrice,
                            'initial_payment' => $initialPayment,
                            'remaining_balance' => $remainingBalance,
                            'reserved_debt_date' => $order->order_date,
                            'paymentType' => $paymentType,
                            'type' => $order->credit ? $order->credit->credit_type : null,
                            'status' => $order->credit ? $order->credit->credit_status : null,
                        ];

                        $overallTotal += $totalPrice;
                    }
                }
            }
        }

        return view('user.order', compact('services', 'products', 'credits', 'orderDetailsData', 'overallTotal'));
    }
    public function userReports() { 
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
            DB::raw('COUNT(tblreturn.ordDet_ID) AS return_count')
        )
        ->join('tblinventory', 'tblproduct.inventory_ID', '=', 'tblinventory.inventory_ID')
        ->join('tblsupplier', 'tblinventory.supplier_ID', '=', 'tblsupplier.supplier_ID')
        ->leftJoin('tblorderitems', 'tblproduct.product_id', '=', 'tblorderitems.product_id')
        ->leftJoin('tblorderreceipt', 'tblorderitems.orderitems_id', '=', 'tblorderreceipt.orderitems_id') // Join with order receipt
        ->leftJoin('tblreturn', 'tblorderreceipt.ordDet_ID', '=', 'tblreturn.ordDet_ID') // Now join tblreturn through tblorderreceipt
        ->where('tblproduct.archived', false)
        ->groupBy(
            'tblproduct.product_id', 
            'tblproduct.product_name',
            'tblproduct.categoryName',
            'tblsupplier.supplier_name',
            'tblinventory.stock_qty',
            'tblproduct.unit_price',
            'tblproduct.prod_add',
            'tblinventory.nextRestockDate'
        )
        ->get();
    
        return view('user.reports', compact('products'));
    } 
}
