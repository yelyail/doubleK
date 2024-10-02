<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tblcustomer;
use App\Models\tblpaymentmethod;
use App\Models\tblorderitems;
use App\Models\tblorderreceipt;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF library
use Illuminate\Support\Facades\DB; // For transaction handling

class orderReceipt extends Controller
{
    public function storeReceipt(Request $request)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            // Validate the incoming request data
            $request->validate([
                'name' => 'nullable|string|max:255',
                'address' => 'required|string|max:255',
                'payment_type' => 'required|string|max:50',
                'product_id' => 'required|integer',
                'qty_order' => 'required|integer|min:1',
                'total_price' => 'required|numeric',
                'delivery_date' => 'required|date',
                'order_date' => 'required|date',
                'paymentDetails' => 'required|array',
                'paymentDetails.senderName' => 'nullable|string|max:255', // GCash
                'paymentDetails.accHold' => 'nullable|string|max:255', // Bank Transfer
                'paymentDetails.cashPayment' => 'nullable|numeric', // Cash
                'paymentDetails.gcashAmount' => 'nullable|numeric', // GCash
                'paymentDetails.bankPayment' => 'nullable|numeric', // Bank Transfer
                'paymentDetails.gcashReferenceNum' => 'nullable|string|max:100', // GCash Ref
                'paymentDetails.bankReferenceNum' => 'nullable|string|max:100', // Bank Transfer Ref
                'paymentDetails.bankName' => 'nullable|string|max:255', // Bank Transfer
            ]);

            // Create a new customer
            $customer = tblcustomer::create([
                'customer_name' => $request->name,
                'address' => $request->address,
                'sender_name' => $request->paymentDetails['senderName'] ?? null, // GCash
                'account_holder' => $request->paymentDetails['accHold'] ?? null, // Bank Transfer
            ]);

            // Prepare payment data based on the payment type
            $paymentData = [
                'payment_type' => $request->payment_type,
                'reference_num' => null,
                'payment' => null,
            ];

            if ($request->payment_type === 'BankTransfer') {
                $paymentData['payment_type'] = $request->paymentDetails['bankName'] ?? $request->payment_type;
                $paymentData['reference_num'] = $request->paymentDetails['bankReferenceNum'] ?? null;
                $paymentData['payment'] = $request->paymentDetails['bankPayment'] ?? 0;
            } elseif ($request->payment_type === 'Gcash') {
                $paymentData['reference_num'] = $request->paymentDetails['gcashReferenceNum'] ?? null;
                $paymentData['payment'] = $request->paymentDetails['gcashAmount'] ?? 0;
            } elseif ($request->payment_type === 'Cash') {
                $paymentData['payment'] = $request->paymentDetails['cashPayment'] ?? 0;
            }

            // Create a new payment method
            $payment = tblpaymentmethod::create($paymentData);

            // Create a new order item
            $orderItem = tblorderitems::create([
                'product_id' => $request->product_id,
                'qty_order' => $request->qty_order,
                'total_price' => $request->total_price,
            ]);

            // Create a new order receipt
            $orderReceipt = tblorderreceipt::create([
                'customer_id' => $customer->id,
                'payment_id' => $payment->id,
                'orderitems_id' => $orderItem->id,
                'delivery_date' => $request->delivery_date,
                'order_date' => $request->order_date,
            ]);

            // Prepare the data for the receipt PDF
            $data = [
                'title' => 'Order Receipt',
                'date' => now()->format('l, F j, Y'),
                'branch' => 'Double-K Computer Parts',
                'warehouse' => '#20 Pag-Asa Street, S.I.R. Matina, Phase 2, Barangay Bucana, Davao City',
                'client' => $customer->customer_name,
                'delivery_address' => $customer->address,
                'contact_person' => $request->name, 
                'logistic_mode' => '', 
                'remark' => '', 
                'print_time' => now()->format('m/d/Y'),
                'items' => [
                    [
                        'item_name' => $orderItem->product->product_name, // Assuming product relation exists
                        'quantity' => $orderItem->qty_order,
                        'price' => $orderItem->product->price, // Assuming product relation exists
                        'discount' => 0, // Add discount logic if necessary
                        'net_price' => $orderItem->total_price,
                        'amount' => $orderItem->total_price * $orderItem->qty_order
                    ]
                ],
                'total' => $orderItem->total_price,
                'current_dr_total' => $orderItem->total_price,
                'manager_approval' => '', 
                'customer_received_date' => '', 
                'checker_name' => '', 
                'checker_signature' => '', 
                'receiver_name' => '', 
                'receiver_signature' => '' 
            ];

            // Generate the receipt PDF
            $pdf = Pdf::loadView('orderReceipt.invoice', $data);
            $pdf->setPaper('A4', 'landscape');

            DB::commit(); // Commit the transaction if everything is successful

            // Return or download the PDF
            return $pdf->download('orderReceipt.pdf');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction if there's any error
            return back()->withErrors(['error' => 'Order processing failed. Please try again.']);
        }
    }
}
