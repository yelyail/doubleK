<?php

namespace App\Http\Controllers;

use App\Models\tblcategory;
use App\Models\tblinventory;
use App\Models\User;
use App\Models\tblcustomer;
use App\Models\tblproduct;
use App\Models\tblservice;
use App\Models\tblsupplier;
use App\Models\tblorderreceipt;
use App\Models\tblpaymentmethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;

class adminaccess extends Controller
{
    public function adminDashboard(){ 
        return view('admin.dashboard');
    }
    public function adminEmployee() {
        $clients = User::all();
        return view('admin.client', compact('clients'));
    }
    public function adminInventory()
    {
        $products = tblproduct::select(
            'tblproduct.product_id',
            'tblcategory.categoryName',
            'tblproduct.product_name',
            'tblsupplier.supplier_name',
            'tblproduct.product_desc',
            'tblproduct.warranty',
            'tblproduct.unit_price',
            'tblinventory.stock_qty',
            'tblproduct.prod_add',
            'tblproduct.updatedQty',
            'tblinventory.nextRestockDate',
            'tblproduct.archived' 
        )
        ->join('tblcategory', 'tblproduct.category_id', '=', 'tblcategory.category_id')
        ->join('tblinventory', 'tblproduct.inventory_ID', '=', 'tblinventory.inventory_ID')
        ->join('tblsupplier', 'tblinventory.supplier_ID', '=', 'tblsupplier.supplier_ID')
        ->where('tblproduct.archived', '=', false) 
        ->get();

        $suppliers = tblsupplier::all();
        $categories = tblcategory::all();

        return view('admin.inventory', compact('products', 'suppliers', 'categories'));
    }

    public function adminOrder(){ 
        $products = tblproduct::all();
        $services = tblservice::all();
        $category = tblcategory::all();
        $orderDetails = tblorderreceipt::with('product')->get();
        $orderDetailsData = [];
        $overallTotal = 0;

        foreach ($orderDetails as $orderDetail) {
            $orderDetailsData[] = [
                'product_name' => $orderDetail->product->product_name,
                'qty_order' => $orderDetail->qty_order,
                'unit_price' => $orderDetail->product->unit_price,
                'total_price' => $orderDetail->total_price,
            ];

            $overallTotal += $orderDetail->total_price;
        }
        return view('admin.order', compact('services', 'products','orderDetails', 'category'));
    }
    public function adminInventoryReports(){ 
        return view('admin.reports');
    }
    public function adminSalesReport(){ 
        return view('admin.salesReport');
    }
    public function adminReservation(){ 
        return view('admin.reservation');
    }
    public function adminService(){ 
        $services = tblservice::all();
        return view('admin.service', compact('services'));
    }
    public function adminSupplier()
    {
        $suppliers = tblsupplier::with('user')->get();

        $users = User::where(function($query) {
            $query->where('archived', 0)
                ->orWhereNull('archived');
        })->get();        
        
        return view('admin.supplier', compact('suppliers', 'users'));
    }

    public function adminCustInfo(){
        return view('admin.custInfo');
    }
    public function adminConfirm(){
        $order = session()->get('order', []);
        return view('admin.confirm', compact('order'));
    }
    public function storeCustomerInfor(Request $request)
    {
        $orderDetails = json_decode($request->input('orderDetails'), true);
        $overallTotal = $request->input('overallTotal');

        // Pass data to the view
        return view('admin.custInfo', compact('orderDetails', 'overallTotal'));
    }
    // for storing customer inforamtion
    public function storeCustomer(Request $request)
    {
        $request->validate([
            'custName' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'deliveryMethod' => 'required|string|in:deliver,pick-up',
            'deliveryDate' => 'required_if:deliveryMethod,deliver|date|nullable',
            'paymentType' => 'required|string|in:cash,gcash,banktransfer',

            'cashPayment' => 'required_if:paymentType,cash|nullable|numeric',

            'gcashCustomerName' => 'required_if:paymentType,gcash|nullable|string|max:255',
            'gcashPayment' => 'required_if:paymentType,gcash|nullable|numeric',
            'gcashReferenceNum' => 'required_if:paymentType,gcash|nullable|string|max:255',

            'bankPaymentType' => 'required_if:paymentType,banktransfer|nullable|string|max:255',
            'bankCustomerName' => 'required_if:paymentType,banktransfer|nullable|string|max:255',
            'bankPayment' => 'required_if:paymentType,banktransfer|nullable|numeric',
            'bankTransactionDate' => 'required_if:paymentType,banktransfer|nullable|date',
            'bankReferenceNum' => 'required_if:paymentType,banktransfer|nullable|string|max:255',
        ]);

        $customer = tblcustomer::updateOrCreate(
            ['customer_name' => $request->custName],
            ['address' => $request->address, 'transaction_date' => $request->bankTransactionDate]
        );

        // Create payment method
        $payment = new tblpaymentmethod();
        if ($request->paymentType == 'cash') {
            $payment->payment_type = 'cash';
            $payment->payment = $request->cashPayment;
        } elseif ($request->paymentType == 'gcash') {
            $payment->payment_type = 'gcash';
            $payment->payment = $request->gcashPayment;
            $payment->reference_num = $request->gcashReferenceNum;
        } elseif ($request->paymentType == 'banktransfer') {
            $payment->payment_type = 'banktransfer';
            $payment->payment = $request->bankPayment;
            $payment->reference_num = $request->bankReferenceNum;
        }
        $payment->save();

        $order = tblorderreceipt::create([
            'customer_id' => $customer->customer_id,
            'payment_id' => $payment->payment_id,
            'order_date' => now(),
            'delivery_date' => $request->deliveryDate,
            'order_status' => 'Pending',
        ]);
        return redirect()->route('adminConfirm')->with('success', 'Order placed successfully!');
    }

    //mag add ug order sa
    public function storeOrder(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'customer_id' => 'required',
            'service_ID' => 'required',
            'payment_id' => 'required',
            'product_id' => 'required',
            'qty_order' => 'required|integer',
            'total_price' => 'required|numeric',
            'delivery_date' => 'nullable|date',
            'order_date' => 'nullable|date',
            'payment_method' => 'required|string',
        ]);

        // Create a new order in the database
        $order = new tblorderreceipt(); // Replace 'Order' with your actual Order model
        $order->customer_id = $validatedData['customer_id'];
        $order->service_ID = $validatedData['service_ID'];
        $order->payment_id = $validatedData['payment_id'];
        $order->product_id = $validatedData['product_id'];
        $order->qty_order = $validatedData['qty_order'];
        $order->total_price = $validatedData['total_price'];
        $order->order_date = $validatedData['order_date'];
        $order->delivery_date = $validatedData['delivery_date'];
        $order->save();

        // Return a response
        return response()->json(['success' => true, 'order' => $order]);
    }


    //for posting 
    public function storeProduct(Request $request)
    {
        $request->validate([
            'categoryName' => 'required|string',
            'productName' => 'required|string',
            'productDescription' => 'required|string',
            'stocks' => 'required|numeric',
            'pricePerUnit' => 'required|numeric',
            'dateAdded' => 'required|date',
            'warrantyPeriod'=> 'required|numeric',
            'supplierName' => 'required|exists:tblsupplier,supplier_ID',
        ]);

        try {
            $category = tblcategory::firstOrCreate(
                ['categoryName' => $request->categoryName],
                ['categoryStatus' => 'active']
            );

            $product = tblproduct::create([
                'category_id' => $category->category_id,
                'product_name' => $request->productName,
                'product_desc' => $request->productDescription,
                'unit_price' => $request->pricePerUnit, 
                'prod_add' => $request->dateAdded,
                'warranty' => $request->warrantyPeriod,
                'archived' => 0
            ]);

            $inventory = tblinventory::create([
                'supplier_ID' => $request->supplierName,
                'stock_qty' => $request->Stocks,
            ]);

            // Link product to inventory
            $product->update([
                'inventory_ID' => $inventory->inventory_ID,
            ]);

            return redirect()->back()->with('success', 'Product and inventory added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add product and inventory');
        }
    }
    public function storeClient(Request $request){
        $request->validate([
            'fullname' => ['required', function ($attribute, $value, $fail) {
                if (User::where('fullname', $value)->exists()) {
                    $fail('The fullname has already been registered.');
                }
            }],
            'username' => ['required', 'unique:user,username'],
            'jobtype' => ['required'],
            'user_contact' => ['required'],
            'password' => ['required', 'min:8'],
        ]);

        try {
            User::create([
                'fullname' => $request->fullname,
                'username' => $request->username,
                'jobtype' => $request->jobtype,
                'user_contact' => $request->user_contact,
                'password' => Hash::make($request->password),
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
    public function storeService(Request $request){
        $service = new tblservice;
        $service->service_name = $request->serviceName;
        $service->description = $request->description;
        $service->service_fee = $request->serviceFee;
        $service->save();
        return redirect()->back();
    }
    public function storeSupplier(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required|string|max:255',
            'supplier_email' => 'required|email',
            'supplier_contact' => 'required|digits:10', // 10 digits for the phone number
            'supplier_landline' => 'nullable|digits_between:7,8', // 7 or 8 digits for the landline
            'supplier_address' => 'required|string',
            'representative' => 'required|exists:user,user_ID',
        ]);

        $supplier = new tblsupplier();
        $supplier->supplier_name = $request->supplier_name;
        $supplier->supplier_email = $request->supplier_email;
        $supplier->supplier_contact = $request->supplier_contact;
        $supplier->supplier_landline = $request->supplier_landline;
        $supplier->supplier_address = $request->supplier_address;
        $supplier->user_ID = $request->representative;
        $supplier->save();

        return redirect()->back()->with('success', 'Supplier added successfully!');
    }

    // --------------------------------------------------
    //for the progress \
    public function addProduct(Request $request)
    {
        // Validate request
        $validatedData = $request->validate([
            'product_id' => 'required|exists:tblproduct,product_id',
            'qty_order' => 'required|integer|min:1',
        ]);

        $product = tblproduct::findOrFail($validatedData['product_id']);

        $total_price = $product->unit_price * $validatedData['qty_order'];

        tblorderreceipt::create([
            'product_id' => $validatedData['product_id'],
            'qty_order' => $validatedData['qty_order'],
            'total_price' => $total_price,
            'order_date' => now(),
        ]);
        return redirect()->back()->with('success', 'Product added to order successfully.');
    }
    public function addServices(Request $request){
        $validatedData = $request->validate([
            'service_id' => 'required|exists:tblservice,service_id',
            'qty_order' => 'required|integer|min:1',
        ]);

        $service = tblservice::findOrFail($validatedData['service_id']);
        $total_price = $service->service_fee * $validatedData['qty_order'];

        tblorderreceipt::create([
            'service_ID' => $validatedData['service_id'],
            'qty_order' => $validatedData['qty_order'],
            'total_price' => $total_price,
            'order_date' => now(),
        ]);
        return redirect()->back()->with('success', 'Service added to order successfully.');
    }

    //For editing a content
    public function editClient($user_ID)
    {
        $client = User::findOrFail($user_ID);
        return response()->json($client);
    }
    public function updateClient(Request $request, $user_ID)
    {
        $request->validate([
            'fullname' => 'required',
            'username' => 'required|unique:user,username,' . $user_ID . ',user_ID',
            'jobtitle' => 'required',
            'user_contact' => 'required',
            'password' => 'nullable|min:8',
        ]);

        $client = User::findOrFail($user_ID);
        
        $data = [
            'fullname' => $request->fullname,
            'username' => $request->username,
            'jobtitle' => $request->jobtitle,
            'user_contact' => $request->user_contact,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password); 
        }

        $client->update($data);
        
        return redirect()->route('adminEmployee')->with('success', 'Employee updated successfully.');
    }

    public function archiveClient($id)
    {
        $client = User::find($id); 
        if ($client) {
            $client->archived = true;
            $client->save();
            return response()->json(['message' => 'Employee archived successfully.']);
        }
        return response()->json(['message' => 'Employee not found.'], 404);
    }
    public function archiveSupplier($id)
    {
        $supplier = tblsupplier::find($id); 
        if ($supplier) {
            $supplier->archived = true;
            $supplier->save();
            return response()->json(['message' => 'Supplier archived successfully.']);
        }
        return response()->json(['message' => 'Supplier not found.'], 404);
    } 
    public function updateSupplier(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required|string|max:255',
            'supplier_email' => 'required|email',
            'supplier_contact' => 'required|string|max:10',
            'supplier_landline' => 'nullable|string|max:10',
            'supplier_address' => 'nullable|string|max:255',
            'representative' => 'required|exists:user,user_ID',
        ]);
        $supplier = tblsupplier::find($request->id);
        if ($supplier) {
            $supplier->supplier_name = $request->supplier_name;
            $supplier->supplier_email = $request->supplier_email;
            $supplier->supplier_contact = $request->supplier_contact;
            $supplier->supplier_landline = $request->supplier_landline;
            $supplier->supplier_address = $request->supplier_address;
            $supplier->user_ID = $request->representative;
            $supplier->save(); 

            return redirect()->back()->with('success', 'Supplier updated successfully!');;
        } else {
            return response()->json(['error' => 'Supplier not found!'], 404);
        }
    }
    public function editSupplier($id)
    {
        $supplier = tblsupplier::find($id);
        if ($supplier) {
            return response()->json($supplier);  // Return the supplier data as JSON
        } else {
            return response()->json(['error' => 'Supplier not found!'], 404);
        }
    }
    public function updateService(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'required|string',
            'service_fee' => 'required|numeric',
        ]);
        $service = tblservice::find($request->id);
        if ($service) {
            $service->service_name = $request->service_name;
            $service->description = $request->description;
            $service->service_fee = $request->service_fee;
            $service->save(); 

            return redirect()->back()->with('success', 'Services updated successfully!');;
        } else {
            return response()->json(['error' => 'Services not found!'], 404);
        }
    }
    public function editService($id)
    {
        $services = tblservice::find($id);
        if ($services) {
            return response()->json($services);  
        } else {
            return response()->json(['error' => 'Services not found!'], 404);
        }
    }
    //humana litsiii 
    public function editProduct($product_id)
    {
        $product = tblproduct::with(['category', 'inventory.supplier'])->find($product_id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json([
            'product_id' => $product->product_id,
            'categoryName' => optional($product->category)->categoryName ?? '',
            'product_name' => $product->product_name,
            'product_desc' => $product->product_desc,
            'stock_qty' => optional($product->inventory)->stock_qty ?? '',
            'unit_price' => $product->unit_price,
            'prod_add' => $product->prod_add,
            'warranty' => $product->warranty,
            'warrantyUnit' => $product->warrantyUnit ?? '',
            'supplier_ID' => optional($product->inventory->supplier)->supplier_name ?? ''
        ]);
    }
    public function updateInventory(Request $request, $productID)
    {
        $request->validate([
            'editStocks' => 'required|integer',
            'editRestockAdded' => 'nullable|date',
            'editWarrantyPeriod' => 'required|numeric', 
            'editPricePerUnit' => 'required|numeric', 
        ]);
        $product = tblproduct::with('inventory')->findOrFail($productID);
       
        if (!$product->inventory) {
            return response()->json(['message' => 'Inventory not found for this product.'], 404);
        }

        $currentStock = $product->inventory->stock_qty ?? 0;
        $updatedQty = $request->input('editStocks', 0); 

        $newStockQty = $currentStock + $updatedQty;

        $product->inventory->stock_qty = $newStockQty;

        $product->updatedQty = $updatedQty; 

        if ($request->input('editWarrantyPeriod')) {
            $product->warranty = $request->input('editWarrantyPeriod'); 
        }
        if ($request->input('editPricePerUnit')) {
            $product->unit_price = $request->input('editPricePerUnit'); 
        }
        if ($request->input('editRestockAdded')) {
            $product->inventory->nextRestockDate = $request->input('editRestockAdded');
        }

        $inventorySaved = $product->inventory->save();
        $productSaved = $product->save();

        if (!$inventorySaved || !$productSaved) {
            return response()->json(['message' => 'Failed to update inventory.'], 500);
        }

        return response()->json([
            'message' => 'Product updated successfully.',
            'newStock' => $newStockQty,
            'updatedQty' => $updatedQty
        ]);
    }
    public function archiveInventory($product_id)
    {
        $product = tblproduct::find($product_id); 
        if ($product) {
            $product->archived = true;
            $product->save();
            return response()->json(['message' => 'Product archived successfully.']);
        }
        return response()->json(['message' => 'Product not found.'], 404);
    }

}
