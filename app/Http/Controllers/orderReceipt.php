<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tblcustomer;
use App\Models\tblpaymentmethod;
use App\Models\tblorderitems;
use App\Models\tblorderreceipt;
use App\Models\tblservice;
use App\Models\tblcredit;
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
                $orderReceipt = tblorderreceipt::create([ 
                    'orderitems_id' => $orderitems_id,
                    'customer_id' => $customer->customer_id,
                    'payment_id' => $payment->payment_id,
                    'delivery_date' => $validatedData['deliveryDate'],
                    'order_date' => $validatedData['billingDate'],
                ]);
            }
            $lastOrderReceiptId = $orderReceipt->ordDet_ID;
            return response()->json([
                'success' => true,
                'message' => 'Order has been placed successfully!',
                'ordDet_ID' => $lastOrderReceiptId 
            ]);
        } catch (\Exception $e) {
            Log::error('Database operation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Database operation failed: ' . $e->getMessage()], 500);
        }
    }
    public function generatePdf($orderitems_id)
    {   
        Log::info("Received ordDet_ID: " . $orderitems_id);
        $orderReceipt = tblorderreceipt::with('orderitems')->findOrFail($orderitems_id);
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
                $totalPrice += $item->total_price;
            }
        }

        $amountPaid = $payment->payment;
        $amountDeducted = $amountPaid - $totalPrice;

        $data = [
            'title' => 'Print Receipt',
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
    }    public function tempReceipt()
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
    public function storeCredit(Request $request){
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
            'orderItems.*.total' => 'required|numeric',
            'credit_type' => 'required|string',
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
            $lastOrdDetID = null; 
            foreach ($orderItemsIds as $orderitems_id) {
                $receipt = tblorderreceipt::create([
                    'orderitems_id' => $orderitems_id,
                    'customer_id' => $customer->customer_id,
                    'payment_id' => $payment->payment_id,
                    'delivery_date' => $validatedData['deliveryDate'],
                    'order_date' => $validatedData['billingDate'],
                ]);
                $lastOrdDetID = $receipt->ordDet_ID; // Store the last created receipt ID
            }
            tblcredit::create([
                'ordDet_ID' => $lastOrdDetID,
                'credit_type' => $validatedData['credit_type'],
                'credit_status' => 'active', 
                'credit_date' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Order has been placed successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Database operation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Database operation failed: ' . $e->getMessage()], 500);
        }
    }   
    public function cancel($creditID)
    {
        $order = tblcredit::findOrFail($creditID);
        $order->credit_status = 'cancel'; 
        $order->save();
        $orderReceipts = tblorderreceipt::where('ordDet_ID', $creditID)->get();

        foreach ($orderReceipts as $item) {
            $orderItem = tblorderitems::find($item->orderitems_id);
            
            if ($orderItem) {
                if ($orderItem->product_id) {
                    $product = tblproduct::with('inventory')->find($orderItem->product_id);
                    
                    if ($product) {
                        Log::info('Product found:', $product->toArray());

                        if ($product->inventory) {
                            $inventory = $product->inventory;
                            $inventory->stock_qty += $orderItem->qty_order; 
                            $inventory->save();
                            Log::info('Updated Inventory:', $inventory->toArray());
                        } else {
                            Log::warning('Inventory not found for product:', $product->toArray());
                        }
                    } else {
                        Log::warning('Product not found for order item:', $orderItem->toArray());
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Order cancelled successfully.');
    }
    public function confirmPayment(Request $request) {
        $creditID = $request->input('creditID');
        $credit = tblcredit::with('orderReceipt.customer', 'orderReceipt.orderItems')->find($creditID);
        if (!$credit) {
            return response()->json([
                'success' => false,
                'message' => 'Credit record not found.'
            ], 404);
        }

        // Prepare the data for the report
        $order = $credit->orderReceipt;
        $customer = $order->customer;

        $reportData = [
            'customer_name' => $customer->customer_name,
            'address' => $customer->address,
            'total_price' => $order->total_price,
            'remaining_balance' => $order->remaining_balance,
            'payment_type' => $order->payment->payment_type,
            'date' => now()->format('Y-m-d'),
            'order_items' => $order->orderItems->toArray(),
            'representative' => auth()->user()->fullname,
        ];

        // Generate the report
        return $this->createPdfReport($reportData);
    }
    private function createPdfReport($data) {
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('reportTemplate', $data)->render());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->stream('orderReport.pdf');
    }
    
}
