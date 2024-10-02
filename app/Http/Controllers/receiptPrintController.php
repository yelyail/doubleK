<?php

namespace App\Http\Controllers;

use App\Models\tblorderitems;
use App\Models\tblorderreceipt;
use App\Models\tblpaymentmethod;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class receiptPrintController extends Controller
{
    public function receipt()
    {
        return view('orderreceiptPrint');
    }
    public function orderReceipt()
    {
        $reference = uniqid();
        $orderItems = tblorderitems::all();
        $customer = tblorderitems::all();
        $payment = tblpaymentmethod::all();
        $orderreceipt = tblorderreceipt::all();
        $data = [
            'title' => 'Temporary Receipts',
            'reference' => $reference,
            'date' => date('m/d/Y'),
            'orderItems' => $orderItems,
            'customer' => $customer,
            'payment' => $payment,
            'orderreceipt' => $orderreceipt,
        ];

        $pdf = Pdf::loadView('orderreceiptPrint', $data);
        $pdf->setPaper('A4', 'landscape');

        // Download or display the PDF
        return $pdf->download('orderReceipt.pdf');
    }
}
