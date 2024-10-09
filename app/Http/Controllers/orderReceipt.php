<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tblcustomer;
use App\Models\tblpaymentmethod;
use App\Models\tblorderitems;
use App\Models\tblorderreceipt;
use App\Models\tblservice;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\tblproduct; 
use Illuminate\Support\Facades\Log; 

class orderReceipt extends Controller
{
    public function storeReceipt(Request $request)
    {
        $validatedData = $request->validate([
            'customerName' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
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
            $customer = tblcustomer::create([
                'customer_name' => $validatedData['customerName'],
                'address' => $validatedData['address'],
            ]);
            $payment = tblpaymentmethod::create([
                'payment_type' => $validatedData['paymentMethod'],
                'reference_num' => $validatedData['referenceNum'],
                'payment' => $validatedData['payment'],
            ]);

            $orderItemsIds = [];
            $productsToUpdate = [];

            foreach ($validatedData['orderItems'] as $item) {
                $productId = $item['type'] === 'product' ? $item['id'] : null;
                $serviceId = $item['type'] === 'service' ? $item['id'] : null;

                $orderItem = tblorderitems::create([
                    'product_id' => $productId,
                    'service_ID' => $serviceId,
                    'qty_order' => $item['quantity'],
                    'total_price' => $item['total'],
                ]);

                $orderItemsIds[] = $orderItem->orderitems_id;
                if ($item['type'] === 'product') {
                    $productsToUpdate[] = [
                        'id' => $item['id'],
                        'quantity' => $item['quantity']
                    ];
                }
            }
            foreach ($productsToUpdate as $productUpdate) {
                $product = tblproduct::with('inventory')->find($productUpdate['id']);
                if ($product && $product->inventory) {
                    $inventory = $product->inventory;
                    $inventory->stock_qty -= $productUpdate['quantity'];
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
                'message' => 'Order has been placed successfully!',
                'ordDet_ID' => end($orderItemsIds) // Fix the issue here
            ]);
        } catch (\Exception $e) {
            Log::error('Database operation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Database operation failed: ' . $e->getMessage()], 500);
        }
    }
    public function generatePdf(Request $request, $ordDet_ID)
    {
        $orderReceipt = tblorderreceipt::with('orderitems')->findOrFail($ordDet_ID);
        $customer = tblcustomer::findOrFail($orderReceipt->customer_id);
        $payment = tblpaymentmethod::findOrFail($orderReceipt->payment_id);
        $representative = auth()->user()->fullname;

        $orderReceipts = tblorderreceipt::with('orderitems')
            ->where('customer_id', $orderReceipt->customer_id)
            ->where('order_date', $orderReceipt->order_date)
            ->get();

        $reference = uniqid();

        $orderItems = collect();
        $totalPrice = 0;

        foreach ($orderReceipts as $receipt) {
            foreach ($receipt->orderitems as $item) {
                $product = $item->product;
                $service = $item->service;

                // Add item to the collection
                $orderItems->push([
                    'product_name' => $product ? $product->product_name : null,
                    'service_name' => $service ? $service->service_name : null,
                    'quantity' => $item->qty_order,
                    'total_price' => $item->total_price,
                ]);

                // Increment total price
                $totalPrice += $item->total_price;
            }
        }

        $amountPaid = $payment->payment;
        $amountDeducted = $amountPaid - $totalPrice;

        // Prepare data for the view
        $data = [
            'title' => 'Temporary Receipts',
            'reference' => $reference,
            'date' => now()->format('m/d/Y H:i:s'),
            'orderItems' => $orderItems,
            'customer_name' => $customer->customer_name,
            'address' => $customer->address,
            'payment_type' => $payment->payment_type,
            'total_price' => number_format($totalPrice, 2),
            'payment' => number_format($amountPaid, 2),
            'amount_deducted' => $amountDeducted >= 0 ? number_format($amountDeducted, 2) : '0.00',
            'representative' => $representative,
        ];

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('orderreceiptPrint', $data)->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->stream('orderReceipt.pdf');
    }

    public function tempReceipt()
    {
        $reference = uniqid();
        $orderItems = tblorderitems::all();
        $customer = tblcustomer::first(); 
        $payment = tblpaymentmethod::first();
        
        $representative = auth()->user()->fullname;

        $items = $orderItems->map(function ($item) {
            $product = $item->product; 
            $service = $item->service; 

            return [
                'product_name' => $product ? $product->name : null, 
                'service_name' => $service ? $service->name : null, 
                'quantity' => $item->qty_order,
                'total_price' => $item->total_price,
            ];
        });

        $data = [
            'title' => 'Temporary Receipts',
            'reference' => $reference,
            'date' => now()->format('m/d/Y H:i:s'),
            'orderItems' => $items, 
            'customer_name' => $customer->customer_name ?? 'N/A', 
            'address' => $customer->address ?? 'N/A', 
            'payment_type' => $payment->payment_type ?? 'N/A', 
            'representative' => $representative,
        ];

        return view('admin.order', $data);
    }


    public function storeReservation(Request $request){
        $request->validate([
            'reservationItems' => 'required|array',
            'deliveryMethod' => 'required|string',
            'deliveryDate' => 'required|string',
            'finalTotal' => 'required|numeric',
        ]);
        foreach ($request->reservationItems as $item) {
            tblorderitems::create([
                'product_id' => $item['product_id'],
                'service_ID' => $item['service_id'],
                'qty_order' => $item['quantity'],
                'total_price' => $item['total'],
            ]);
        }

        // Return a success response
        return response()->json(['message' => 'Reservation made successfully!']);
    }
}
