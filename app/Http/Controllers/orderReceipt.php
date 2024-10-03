<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tblcustomer;
use App\Models\tblpaymentmethod;
use App\Models\tblorderitems;
use App\Models\tblorderreceipt;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF library
use Illuminate\Support\Facades\DB; // For transaction handling
use App\Models\tblproduct; // Import tblproduct model

class orderReceipt extends Controller
{
    public function storeReceipt(Request $request)
    {
        // Step 1: Store customer information
        $customer = tblcustomer::create([
            'customer_name' => $request->customerInfo['name'],
            'customer_address' => $request->customerInfo['address'],
            'customer_phone' => $request->customerInfo['phone'],
            'customer_email' => $request->customerInfo['email'],
        ]);

        // Step 2: Store payment information
        $payment = tblpaymentmethod::create([
            'payment_type' => 'Cash', // This could be dynamic based on input
            'reference_num' => null,  // Optional
            'payment' => $request->finalTotal, // Total amount paid
        ]);

        // Step 3: Create the order receipt
        $orderReceipt = tblorderreceipt::create([
            'customer_id' => $customer->customer_id,
            'payment_id' => $payment->payment_id,
            'delivery_date' => now()->addDays(3), // Assuming a delivery date of 3 days after
            'order_date' => now(),
        ]);

        // Step 4: Loop through each order item and save it
        foreach ($request->orderItems as $item) {
            tblorderitems::create([
                'orderreceipt_id' => $orderReceipt->ordDet_ID,
                'product_id' => $item['product_id'] ?? null,
                'service_ID' => $item['service_ID'] ?? null,
                'qty_order' => $item['quantity'],
                'total_price' => $item['total'],
            ]);

            // Step 5: Update the product quantity if a product is involved
            if (isset($item['product_id'])) {
                $product = tblproduct::find($item['product_id']);
                $product->updatedQty -= $item['quantity']; // Decrease product quantity
                $product->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Order placed successfully']);
    }

    public function storeReservation(Request $request){
    }
}
