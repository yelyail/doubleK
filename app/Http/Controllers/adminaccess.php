<?php

namespace App\Http\Controllers;

use App\Models\tblinventory;
use App\Models\User;
use App\Models\tblcustomer;
use App\Models\tblproduct;
use App\Models\tblservice;
use App\Models\tblsupplier;
use App\Models\tblorderdetails;
use App\Models\tblpaymentmethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'tblproduct.category_name',
            'tblproduct.product_name',
            'tblsupplier.supplier_name',
            'tblproduct.product_desc',
            'tblproduct.warranty',
            'tblproduct.unit_price',
            'tblinventory.stock_qty', 
            'tblproduct.prod_add',
            'tblproduct.updatedQty',
            'tblinventory.nextRestockDate'
        )
        ->join('tblinventory', 'tblproduct.inventory_ID', '=', 'tblinventory.inventory_id')
        ->join('tblsupplier', 'tblinventory.supplier_ID', '=', 'tblsupplier.supplier_ID')
        ->get();

        $suppliers = tblsupplier::all();

        return view('admin.inventory', compact('products', 'suppliers'));
    }
    public function adminOrder(){ 
        $products = tblproduct::all();
        $services = tblservice::all();
        $orderDetails = tblorderdetails::with('product')->get();
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
        return view('admin.order', compact('services', 'products','orderDetails'));
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

        $order = tblorderdetails::create([
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
        $request->validate([
            ' ' => 'required|integer',
            'service_ID' => 'required|integer',
            'payment_id' => 'required|string',
            'qty_order' => 'required|integer',
            'total_price' => 'required|numeric',
            'delivery_date' => 'nullable|date',
            'order_date' => 'required|date',
        ]);
        tblorderdetails::create($request->all());

        return redirect()->back()->with('success', 'Order placed successfully!');
    }

    //for posting 
    public function storeProduct(Request $request)
    {
        $request->validate([
            'categoryName' => 'required|string',
            'productName' => 'required|string',
            'productDescription' => 'required|string',
            'Stocks' => 'required|numeric',
            'pricePerUnit' => 'required|numeric',
            'dateAdded' => 'required|date',
            'warrantyPeriod'=> 'required|numeric',
            'supplierName' => 'required|numeric',
        ]);

        try {
            $product = tblproduct::create([
                'category_name' => $request->categoryName,
                'product_name' => $request->productName,
                'product_desc' => $request->productDescription,
                'unit_price' => $request->pricePerUnit, 
                'prod_add' => $request->dateAdded,
                'warranty' => $request->warrantyPeriod,
                'supplier_ID' => $request->supplierName,
            ]);
            $inventory = tblinventory::create([
                'supplier_ID' => $request->supplierName,
                'stock_qty' => $request->Stocks,
            ]);

            $product->update([
                'inventory_ID' => $inventory->inventory_id, 
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
            'supplier_contact' => 'required|string|size:10',
            'supplier_landline' => 'nullable|string|size:10',
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

        // Find the product
        $product = tblproduct::findOrFail($validatedData['product_id']);

        // Calculate total price (price * quantity)
        $total_price = $product->unit_price * $validatedData['qty_order'];

        // Create order details
        tblorderdetails::create([
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

        tblorderdetails::create([
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
            'jobtype' => 'required',
            'user_contact' => 'required',
        ]);

        $client = User::findOrFail($user_ID);
        $client->update([
            'fullname' => $request->fullname,
            'username' => $request->username,
            'jobtype' => $request->jobtype,
            'user_contact' => $request->user_contact,
        ]);
    
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
    public function archiveInventory($id)
    {
        $product = tblproduct::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }
        $product->archived = true; 
        $product->save();

        return response()->json(['message' => 'Product archived successfully.']);
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
    // not finish ang edit inventory ug update inventory yawa sigi rag cannot be found edi wow
    public function editInventory($id) 
    {
        $product = tblproduct::find($id);
        dd($product); // Temporarily dump the result to see if it exists

        if ($product) {
            return response()->json($product);
        } else {
            return response()->json(['error' => 'Product not found!'], 404);
        }
    }
    public function updateInventory(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'editCategoryName' => 'required|string|max:255',
            'editProductName' => 'required|string|max:255',
            'editProductDescription' => 'nullable|string',
            'editUpdatedStocks' => 'nullable|integer',
            'editPricePerUnit' => 'required|numeric',
            'editRestockDate' => 'nullable|date',
            'warrantyPeriod' => 'nullable|integer',
            'editSupplierName' => 'required|integer',
        ]);

        // Find the product by ID
        $product = tblproduct::find($request->product_id);

        // Check if product exists
        if ($product) {
            // Update product details
            $product->category_name = $request->editCategoryName;
            $product->product_name = $request->editProductName;
            $product->product_desc = $request->editProductDescription;
            $product->updatedQty = $request->editUpdatedStocks; 
            $product->unit_price = $request->editPricePerUnit;
            $product->warranty = $request->warrantyPeriod;

            // Save the updated product data
            $product->save();

            // Check if the product has related inventory and update
            if ($product->inventory) {
                $product->inventory->nextRestockDate = $request->editRestockDate;
                $product->inventory->supplier_id = $request->editSupplierId;
                $product->inventory->save();
            }

            return redirect()->back()->with('success', 'Inventory updated successfully.');
        }
        return redirect()->back()->with('error', 'Inventory not found.');
    }

}
