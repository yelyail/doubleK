<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tblreturn;
use App\Models\tblcredit;
use App\Models\tblorderreceipt;
use App\Models\tblproduct;
use App\Models\User;
use Dompdf\Dompdf;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Log;

class salesReceiptController extends Controller
{
    /**
     * Display the sales receipt.
     *
     * @return \Illuminate\View\View
     */
public function salesReceipt(Request $request)
{
    $request->validate([
        'from_date' => 'required|date',
        'to_date' => 'required|date|after_or_equal:from_date'
    ], [
        'from_date.required' => 'Please provide a starting date.',
        'to_date.required' => 'Please provide an ending date.',
        'to_date.after_or_equal' => 'The ending date must be the same or later than the starting date.'
    ]);

    $fromDate = \Carbon\Carbon::parse($request->input('from_date'))->startOfDay();
    $toDate = \Carbon\Carbon::parse($request->input('to_date'))->endOfDay();

    $reportCounter = $request->session()->get('report_counter', 1);
    $request->session()->put('report_counter', $reportCounter + 1);

    try {
        $orderReceipts = tblorderreceipt::with(['orderitems.product', 'orderitems.service', 'customer', 'paymentmethod'])
            ->whereBetween('order_date', [$fromDate, $toDate])
            ->get();

        if ($request->has('download')) {
            if ($orderReceipts->isEmpty()) {
                return response()->json(['error' => 'No records found for the selected dates.'], 404);
            }
        
            $reportData = [];
            $totalSales = 0;
            $representative = auth()->user()->fullname;

            foreach ($orderReceipts as $orderReceipt) {
                $customer = $orderReceipt->customer;
                $payment = $orderReceipt->paymentmethod;
                $orderItems = $orderReceipt->orderitems;

                foreach ($orderItems as $item) {
                    $product = $item->product;
                    $service = $item->service;
                    $totalPrice = $item->total_price; 
                    $amount = $payment->payment ?? 0;

                    $reportData[] = [
                        'order_id' => $reportCounter,
                        'customer_name' => $customer->customer_name ?? 'N/A',
                        'particulars' => $product->product_name ?? $service->service_name ?? 'N/A',
                        'quantity_ordered' => $item->qty_order,
                        'unit_price' => $product ? number_format($product->unit_price, 2) : 'N/A',
                        'totalPrice' => number_format($totalPrice, 2),
                        'amount' => number_format($amount, 2),
                        'payment' => number_format($amount, 2),
                        'payment_type' => $payment->payment_type ?? 'N/A',
                        'reference_num' => $payment->reference_num ?? 'N/A',
                        'order_date' => $orderReceipt->order_date,
                        'sales_recipient' => $representative,
                    ];

                    $totalSales += $totalPrice; 
                }
            }
            $data = [
                'title' => 'Sales Report',
                'date' => now()->format('m/d/Y H:i:s'),
                'orderItems' => $reportData,
                'representative' => $representative,
                'total_sales' => number_format($totalSales, 2),
                'order_id' => 'Report #' . $reportCounter,
                'fromDate' => $fromDate->format('Y-m-d'),
                'toDate' => $toDate->format('Y-m-d'),
            ];
            $dompdf = new Dompdf();
            $dompdf->loadHtml(view('salesReportPrint', $data)->render());
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response()->streamDownload(function() use ($dompdf) {
                echo $dompdf->output();
            }, 'salesReport.pdf');
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to generate PDF. Please try again later.'], 500);
    }

    return response()->json(['message' => 'No download requested.'], 200);
}    
public function requestRepair(Request $request)
{
    try {
        $request->validate([
            'ordDet_ID' => 'required|integer|exists:tblorderreceipt,ordDet_ID', 
            'reason' => 'required|string'
        ]);

        $orderReceiptId = $request->input('ordDet_ID');
        $returnReason = $request->input('reason');
        $orderReceipt = tblorderreceipt::find($orderReceiptId);

        if (!$orderReceipt) {
            return response()->json(['error' => 'Order receipt not found.'], 404);
        }

        $currentDate = date('Y-m-d');
        $newReturn = tblreturn::create([
            'ordDet_ID' => $orderReceipt->ordDet_ID, 
            'returnDate' => $currentDate,
            'returnReason' => $returnReason,
            'return_status' => 'complete', 
        ]);
        if ($newReturn) {
            return response()->json(['success' => 'Repair request has been successfully submitted.'], 200);
        } else {
            return response()->json(['error' => 'Failed to submit repair request.'], 500);
        }

    } catch (\Exception $e) {
        Log::error('Error submitting repair request: ' . $e->getMessage());
        return response()->json(['error' => 'An internal error occurred. Please try again later.'], 500);
    }
}
public function updateStatus(Request $request, $creditID)
{
    $request->validate([
        'status' => 'required|in:paid', 
    ]);
    $credit = tblcredit::where('creditID', $creditID)->first(); // Fetch the credit record
    if (!$credit) {
        return response()->json(['error' => 'Credit record not found.'], 404);
    }
    $credit->credit_status = 'paid'; 
    $credit->save();
    return response()->json(['success' => 'Credit status has been successfully marked as paid.']);
}
public function inventoryReceipt(Request $request)
{
    $admin = auth()->user(); 
    $representative = $admin ? $admin->fullname : 'Unknown Representative';

    $request->validate([
        'from_date' => 'required|date',
        'to_date' => 'required|date|after_or_equal:from_date'
    ], [
        'from_date.required' => 'Please provide a starting date.',
        'to_date.required' => 'Please provide an ending date.',
        'to_date.after_or_equal' => 'The ending date must be the same or later than the starting date.'
    ]);

    $fromDate = \Carbon\Carbon::parse($request->input('from_date'))->startOfDay();
    $toDate = \Carbon\Carbon::parse($request->input('to_date'))->endOfDay();

    $adminUser = User::where('jobtitle', 0)->select('fullname')->first();
    $adminName = $adminUser ? $adminUser->fullname : 'N/A';   

    $products = tblproduct::with(['inventory.supplier'])
        ->whereBetween('prod_add', [$fromDate, $toDate])
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
    if ($products->isEmpty()) {
        return response()->json(['message' => 'No products found for the selected date range.'], 404);
    }
    $data = [
        'title' => 'Inventory Report',
        'date' => now()->format('m/d/Y H:i:s'),
        'representative' => $representative,
        'products' => $products, 
        'adminName' => $adminName,
        'fromDate' => $fromDate->format('Y-m-d'),
        'toDate' => $toDate->format('Y-m-d'),
    ];
    $dompdf = new Dompdf();
    $dompdf->loadHtml(view('inventoryReportPrint', $data)->render());
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    return $dompdf->stream('inventoryReport.pdf', ['Attachment' => true]);
}


}