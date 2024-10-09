<?php

namespace App\Http\Controllers;

use App\Models\tblinventory;
use Illuminate\Http\Request;
use App\Models\tblreturn;
use App\Models\tblorderitems;
use App\Models\tblorderreceipt;
use App\Models\tblproduct;
use App\Models\User;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade as PDF;


class salesReceiptController extends Controller
{
    /**
     * Display the sales receipt.
     *
     * @return \Illuminate\View\View
     */
    public function salesReceipt(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if (!$request->session()->has('report_counter')) {
            $request->session()->put('report_counter', 1);
        }

        $reportCounter = $request->session()->get('report_counter');
        $request->session()->put('report_counter', $reportCounter + 1);

        try {
            $orderReceipts = tblorderreceipt::with(['orderitems', 'customer', 'paymentmethod'])
                ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                    if ($fromDate === $toDate) {
                        return $query->whereDate('order_date', $fromDate);
                    } else {
                    return $query->whereDate('order_date', '>=', $fromDate)
                                    ->whereDate('order_date', '<=', $toDate);
                    }
                })
                ->get();
            
            $reportData = [];
            $totalSales = 0;
            $representative = auth()->user()->fullname; 
            
            if ($request->has('download')) {
                if ($orderReceipts->isEmpty()) {
                    return response()->json(['error' => 'No records found for the selected dates.'], 404);
                }

                foreach ($orderReceipts as $orderReceipt) {
                    $customer = $orderReceipt->customer; 
                    $payment = $orderReceipt->paymentmethod;
                    $orderItems = $orderReceipt->orderitems; 
                    $totalPrice = $orderItems->sum('total_price'); 
                    $amount = $payment->payment;
        
                    foreach ($orderItems as $item) {
                        $product = $item->product; 
                        $service = $item->service;
        
                        $reportData[] = [
                            'order_id' => $reportCounter,
                            'customer_name' => $customer->customer_name ?? 'N/A',
                            'particulars' => $product ? $product->product_name : ($service ? $service->service_name : 'N/A'),
                            'quantity_ordered' => $item->qty_order,
                            'unit_price' => number_format($product->unit_price, 2),
                            'totalPrice' => number_format($totalPrice, 2),
                            'amount' => number_format($amount, 2),
                            'payment' => number_format($amount, 2),
                            'payment_type' => $payment->payment_type ?? 'N/A',
                            'reference_num' => $payment->reference_num ?? 'N/A',
                            'order_date' => $orderReceipt->order_date,
                            'sales_recipient' => $representative,
                        ];
                    }
                    $totalSales += $totalPrice;
                }
        
                $data = [
                    'title' => 'Sales Report',
                    'date' => now()->format('m/d/Y H:i:s'),
                    'orderItems' => $reportData,
                    'representative' => $representative,
                    'total_sales' => number_format($totalSales, 2), 
                    'order_id' => 'Report #' . $reportCounter, 
                    'fromDate'=>$fromDate,
                    'toDate'=>$toDate,
                ];

                $dompdf = new Dompdf();
                $dompdf->loadHtml(view('salesReportPrint', $data)->render()); 
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                return $dompdf->stream('salesReport.pdf', ['Attachment' => true]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate PDF. Please try again later.'], 500);
        }
        return view('salesReportPrint', compact('orderReceipts', 'fromDate', 'toDate'));
    }
    public function requestRepair(Request $request){
        $request->validate([
            'ordDet_ID' => 'required|exists:tblorderitems,orderitems_id', 
            'reason' => 'required|string',
        ]);
    
        $orderItem = tblorderitems::find($request->ordDet_ID);
    
        if ($orderItem) {
            $return = new tblreturn();
            $return->product_id = $orderItem->product_id; 
            $return->returnDate = now();
            $return->returnReason = $request->reason;
            $return->return_status = 'Pending';  
            $return->save();
    
            return response()->json(['success' => 'Repair request submitted successfully!']);
        } else {
            return response()->json(['error' => 'Order item not found!'], 404);
        }
        
    }
    public function inventoryReceipt()
    {
        $admin = auth()->user(); 
        $representative = $admin ? $admin->fullname : 'Unknown Representative';

        $adminUser = User::where('jobtitle', 0)->select('fullname')->first();
        $adminName = $adminUser ? $adminUser->fullname : 'N/A';   
             
        $products = tblproduct::with(['inventory.supplier']) 
            ->get()
            ->map(function ($product) {
                return [
                    'product_name' => $product->product_name,
                    'categoryName' => $product->categoryName,
                    'supplierName' => $product->inventory->supplier->supplier_name ?? 'N/A', 
                    'stock_qty' => $product->inventory->stock_qty ?? 0, 
                    'nextRestockDate' => $product->inventory->nextRestockDate ?? 'N/A',
                    'unit_price' => $product->unit_price,
                    'warranty' => $product->warranty,
                    'product_desc' => $product->product_desc,
                    'prod_add' => $product->prod_add,
                    'updatedQty' => $product->updatedQty ?? 'N/A', 
                ];
            });

        $data = [
            'title' => 'Inventory Report',
            'date' => now()->format('m/d/Y H:i:s'),
            'representative' => $representative,
            'products' => $products, 
            'adminName' => $adminName,
        ];

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('inventoryReportPrint', $data)->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('inventoryReport.pdf', ['Attachment' => true]);
    }



}