<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tblreturn;
use App\Models\tblorderitems;
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
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $reportCounter = $request->session()->get('report_counter', 1);
        $request->session()->put('report_counter', $reportCounter + 1);

        try {
            $orderReceipts = tblorderreceipt::with(['orderitems.product', 'orderitems.service', 'customer', 'paymentmethod'])
                ->whereBetween('order_date', [$fromDate, $toDate])
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
                    $amount = $payment->payment ?? 0;

                    foreach ($orderItems as $item) {
                        $product = $item->product;
                        $service = $item->service;

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
                    'fromDate' => $fromDate,
                    'toDate' => $toDate,
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
                'return_status' => 'ongoing', 
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


    public function updateStatus(Request $request, $ordDet_ID)
    {
        $request->validate([
            'status' => 'required|in:confirm',
        ]);
    
        $return = tblreturn::where('ordDet_ID', $ordDet_ID)->first(); // Use where to find by ordDet_ID
        if (!$return) {
            return response()->json(['error' => 'Order receipt not found.'], 404);
        }
    
        $return->return_status = 'confirmed'; // Ensure this matches your database values
        $return->save();
    
        return response()->json(['success' => 'Status has been successfully confirmed.']);
    }
    




    public function inventoryReceipt(Request $request)
    {
        $admin = auth()->user(); 
        $representative = $admin ? $admin->fullname : 'Unknown Representative';

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        try {
            if ($fromDate) {
                $fromDate = \Carbon\Carbon::parse($fromDate)->startOfDay(); 
            }
            
            if ($toDate) {
                $toDate = \Carbon\Carbon::parse($toDate)->endOfDay(); 
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format.'], 400);
        }

        // Retrieve the admin name for display
        $adminUser = User::where('jobtitle', 0)->select('fullname')->first();
        $adminName = $adminUser ? $adminUser->fullname : 'N/A';   

        // Retrieve products filtered by the date range
        $products = tblproduct::with(['inventory.supplier'])
            ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
                return $query->whereBetween('prod_add', [$fromDate, $toDate]);
            })
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

        // Check if any products were retrieved after filtering
        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found for the selected date range.'], 404);
        }

        // Prepare data for the PDF view
        $data = [
            'title' => 'Inventory Report',
            'date' => now()->format('m/d/Y H:i:s'),
            'representative' => $representative,
            'products' => $products, 
            'adminName' => $adminName,
            'fromDate' => isset($fromDate) ? $fromDate->format('Y-m-d') : null,
            'toDate' => isset($toDate) ? $toDate->format('Y-m-d') : null,
        ];

        // Load the view and render the PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('inventoryReportPrint', $data)->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('inventoryReport.pdf', ['Attachment' => true]);
    }


}