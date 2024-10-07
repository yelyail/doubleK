<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tblcustomer;
use App\Models\tblpaymentmethod;
use App\Models\tblorderitems;
use App\Models\tblorderreceipt;
use App\Models\tblinventory;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF library
use App\Models\tblproduct; // Import tblproduct model
use Illuminate\Support\Facades\Log; // Import Log facade

class orderReceipt extends Controller
{
    public function storeReceipt(Request $request)
    {
        $validatedData = $request->validate([
            'customerName' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'deliveryDate' => 'nullable|date',
            'paymentMethod' => 'required|string',
            'referenceNum' => 'nullable|string',
            'payment' => 'required|numeric',
            'billingDate' => 'required|date',
            'totalAmount' => 'required|numeric',
            'orderItems' => 'required|array',
            'orderItems.*.type' => 'required|in:product,service',
            'orderItems.*.id' => 'required|integer',
            'orderItems.*.quantity' => 'required|integer',
            'orderItems.*.price' => 'required|numeric',
            'orderItems.*.total' => 'required|numeric'
        ]);

        try {
            // Create customer record
            $customer = tblcustomer::create([
                'customer_name' => $validatedData['customerName'],
                'address' => $validatedData['address'],
            ]);

            // Create payment record
            $payment = tblpaymentmethod::create([
                'payment_type' => $validatedData['paymentMethod'],
                'reference_num' => $validatedData['referenceNum'],
                'payment' => $validatedData['payment'],
            ]);

            $orderItemsIds = [];
            $productsToUpdate = [];

            foreach ($validatedData['orderItems'] as $item) {
                // Set product ID and service ID based on the item type
                $productId = $item['type'] === 'product' ? $item['id'] : null;
                $serviceId = $item['type'] === 'service' ? $item['id'] : null;

                // Create order item with appropriate IDs
                $orderItem = tblorderitems::create([
                    'product_id' => $productId,
                    'service_ID' => $serviceId,
                    'qty_order' => $item['quantity'],
                    'total_price' => $item['total'],
                ]);

                $orderItemsIds[] = $orderItem->orderitems_id;

                // If the item is a product, prepare to update its inventory
                if ($item['type'] === 'product') {
                    $productsToUpdate[] = [
                        'id' => $item['id'],
                        'quantity' => $item['quantity']
                    ];
                }
            }

            // Update inventory for products
            foreach ($productsToUpdate as $productUpdate) {
                $product = tblproduct::with('inventory')->find($productUpdate['id']);
                if ($product && $product->inventory) {
                    $inventory = $product->inventory;
                    $inventory->stock_qty -= $productUpdate['quantity'];

                    // Check if there's sufficient stock
                    if ($inventory->stock_qty < 0) {
                        Log::warning('Insufficient stock for product ID ' . $productUpdate['id'] . '. Current stock: ' . $inventory->stock_qty);
                        session()->flash('warning', 'Insufficient stock for product ID ' . $productUpdate['id'] . '. Current stock: ' . $inventory->stock_qty);
                    } else {
                        $inventory->save();
                        Log::info('Inventory updated for product ID ' . $productUpdate['id'] . ': ' . $inventory->stock_qty);
                    }
                } else {
                    Log::warning('No inventory found for product ID ' . $productUpdate['id']);
                }
            }

            // Store order receipts for each order item
            foreach ($orderItemsIds as $orderitems_id) {
                tblorderreceipt::create([
                    'orderitems_id' => $orderitems_id,
                    'customer_id' => $customer->customer_id,
                    'payment_id' => $payment->payment_id,
                    'delivery_date' => $validatedData['deliveryDate'],
                    'order_date' => $validatedData['billingDate'],
                ]);
            }

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
