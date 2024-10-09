<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tblcustomer;
use App\Models\tblpaymentmethod;
use App\Models\tblorderitems;
use App\Models\tblorderreceipt;
use App\Models\tblservice;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\tblproduct;
use App\Models\tblproductcategory;


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
                ->when($fromDate, function ($query) use ($fromDate) {
                    return $query->where('order_date', '>=', $fromDate);
                })
                ->when($toDate, function ($query) use ($toDate) {
                    return $query->where('order_date', '<=', $toDate);
                })->get();
            
            $reportData = [];
            $totalSales = 0;
            $representative = auth()->user()->fullname; 
            
            if ($request->has('download')) {
                // Logic to generate PDF
                $reportData = [];
                $totalSales = 0;
                $representative = auth()->user()->fullname; 
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
    }
}