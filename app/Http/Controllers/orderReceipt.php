<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tblcustomer;
use App\Models\tblpaymentmethod;
use App\Models\tblorderitems;
use App\Models\tblorderreceipt;
use App\Models\tblinventory;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF library
use Illuminate\Support\Facades\DB; // For transaction handling
use App\Models\tblproduct; // Import tblproduct model
use Symfony\Contracts\Service\Attribute\Required;
use Illuminate\Support\Facades\Log; // Import Log facade

class orderReceipt extends Controller
{
    public function storeReceipt(Request $request)
    {
        Log::info('Received request data:', $request->all());

        $validatedData= $request->validate([
            'customerName' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'deliveryDate' => 'required|date',
            'paymentMethod' => 'required|string',
            'referenceNum' => 'nullable|string',
            'payment' => 'required|numeric',
            'billingDate' => 'required|date',
            'totalAmount' => 'required|numeric',
            'orderItems' => 'required|array',
            'orderItems.*.type' => 'required|in:product,service',
            'orderItems.*.id' => 'required_if:orderItems.*.type,product|integer',
            'orderItems.*.quantity' => 'required|integer',
            'orderItems.*.price' => 'required|numeric',
            'orderItems.*.total' => 'required|numeric'
        ]);
        Log::info("log infor", $validatedData);

        try {   
            Log::info('Attempting to create customer...');
            $customer = tblcustomer::create([
                'customer_name' => $validatedData['customerName'],
                'address' => $validatedData['address'],
                'transaction_date' => $validatedData['deliveryDate'],
            ]);
            Log::info('Customer created:', $customer->toArray());

            Log::info('Attempting to create payment method...');
            // Create the payment method
            $payment = tblpaymentmethod::create([
                'payment_type' => $validatedData['paymentMethod'],
                'reference_num' => $validatedData['referenceNum'], 
                'payment' => $validatedData['payment'],
            ]);
            Log::info('Payment method created:', $payment->toArray());
            $orderItemsIds = [];

            if (empty($validatedData['orderItems'])) {
                Log::warning('No order items found in the validated data.');
            }

            foreach ($validatedData['orderItems'] as $item) {
                Log::info('Processing item:', $item);

                if ($item['type'] === 'product') {
                    $orderItem = tblorderitems::create([
                        'product_id' => $item['id'],
                        'qty_order' => $item['quantity'],
                        'total_price' => $item['total'],
                    ]);
                    Log::info('Order item created:', $orderItem->toArray());
                    $orderItemsIds[] = $orderItem->orderitems_id;

                    // Update stock quantity
                    $inventory = tblinventory::where('product_id', $item['id'])->first();
                    if ($inventory) {
                        $inventory->stock_qty -= $item['quantity'];
                        $inventory->save();
                        Log::info('Inventory updated for product ID ' . $item['id'] . ': ' . $inventory->stock_qty);
                    }
                } elseif ($item['type'] === 'service') {
                    // Create order item for service
                    $orderItem = tblorderitems::create([
                        'service_ID' => $item['id'],
                        'qty_order' => $item['quantity'],
                        'total_price' => $item['total'],
                    ]);
                    Log::info('Order item created:', $orderItem->toArray());
                    $orderItemsIds[] = $orderItem->orderitems_id;
                }
            }

            foreach ($orderItemsIds as $orderitems_id) {
                $orderReceipt = tblorderreceipt::create([
                    'orderitems_id' => $orderitems_id, 
                    'customer_id' => $customer->customer_id,
                    'payment_id' => $payment->payment_id,
                    'delivery_date' => $validatedData['deliveryDate'],
                    'order_date' => $validatedData['billingDate'],
                ]);
                Log::info('Order receipt created:', $orderReceipt->toArray());
            }

            Log::info('All operations completed successfully.');
            return response()->json([
                'success' => true,
                'message' => 'Order has been placed successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Database operation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Database operation failed: ' . $e->getMessage()], 500);
        }
    }




    public function storeReservation(Request $request){
    }
}
