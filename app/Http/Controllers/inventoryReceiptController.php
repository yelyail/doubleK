<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tblcustomer;
use App\Models\tblpaymentmethod;
use App\Models\tblorderitems;
use App\Models\tblorderreceipt;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
    

class inventoryReceiptController extends Controller
{
    public function generatePdf(Request $request, $ordDet_ID)
    {
        // Retrieve the order receipt based on ordDet_ID
        $orderReceipt = tblorderreceipt::findOrFail($ordDet_ID);
        $customer = tblcustomer::findOrFail($orderReceipt->customer_id);
        $payment = tblpaymentmethod::findOrFail($orderReceipt->payment_id);

        // Now retrieve the order items
        $orderItems = tblorderitems::where('orderitems_id', $ordDet_ID)->get();

        $reference = uniqid();
        $representative = auth()->user()->fullname;

        $data = [
            'title' => 'Temporary Receipts',
            'reference' => $reference,
            'date' => now()->format('m/d/Y H:i:s'),
            'orderItems' => $orderItems,
            'customer_name' => $customer->customer_name,
            'address' => $customer->address,
            'payment_type' => $payment->payment_type,
            'total_price' => $orderItems->sum('total_price'), // Sum total price from order items
            'payment' => $payment->payment, // Or however you store this
            'representative' => $representative,
        ];
        
        $pdf = PDF::loadView('project.generatePDF', $data);
        return $pdf->download('orderReceipts.pdf');
    }



    public function tempReceipt(){
        $reference = uniqid();
        $orderItems = tblorderitems::all(); // Make sure you only retrieve what's necessary
        $customer = tblcustomer::first(); // Assuming you want the first customer or adjust to get specific one
        $payment = tblpaymentmethod::first(); // Adjust similarly
        $representative = auth()->user()->fullname;
    
        $data = [
            'title' => 'Temporary Receipts',
            'reference' => $reference,
            'date' => date('m/d/Y H:i:s'),
            'orderItems' => $orderItems,
            'customer_name' => $customer->customer_name ?? 'N/A', // Handle case if customer is null
            'address' => $customer->address ?? 'N/A', // Handle similarly
            'payment_type' => $payment->payment_type ?? 'N/A', // Handle similarly
            'representative' => $representative,
        ];
        
        return view('admin.order', $data);
    }
}
