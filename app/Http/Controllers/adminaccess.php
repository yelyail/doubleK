<?php

namespace App\Http\Controllers;

use App\Models\tblinventory;
use App\Models\User;
use App\Models\tblcustomer;
use App\Models\tblproduct;
use App\Models\tblservice;
use App\Models\tblsupplier;
use App\Models\tblorderdetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        return view('admin.order', compact('services', 'products'));
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
    public function custInfo() 
    {
        $customer = tblcustomer::select('customer_id', 'customer_name', 'address')->first();

        return view('admin.custInfo', [
            'customer' => $customer,
            'customer_name' => $customer->customer_name ?? 'N/A',
            'address' => $customer->address ?? 'N/A',
            'customer_id' => $customer->customer_id ?? null
        ]);
    }
    public function storeCustomer(Request $request){
        $request->validate([
            'customer_name' => 'required',  
            'address' => 'required',
        ]);
        try {
            tblcustomer::create([
                'customer_name' => $request->customer_name,
                'address' => $request->address,
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }
    public function confirm(){
        return view('admin.confirm');
    }
    public function addProduct(Request $request)
    {
        $validatedData = $request->validate([
            'service_ID' => 'nullable|integer',
            'customer_id' => 'required|integer',
            'payment_id' => 'required|integer',
            'product_id' => 'required|integer',
            'qty_order' => 'required|integer|min:1'
        ]);
        $product = tblproduct::findOrFail($validatedData['product_id']);

        if (!$product->isAvailable()) {
            return response()->json(['error' => 'Product is not available for purchase.'], 422);
        }
        $totalPrice = $product->price * $validatedData['qty_order'];

        $orderDetail = tblorderdetails::create([
            'service_ID' => $validatedData['service_ID'],
            'customer_id' => $validatedData['customer_id'],
            'payment_id' => $validatedData['payment_id'],
            'product_id' => $validatedData['product_id'],
            'qty_order' => $validatedData['qty_order'],
            'total_price' => $totalPrice
        ]);

        if ($product->stock > 0) {
            $product->update(['stock' => $product->stock - $validatedData['quantity']]);
        }

        // Return a success message
        return response()->json(['message' => 'Order created successfully!', 'orderDetail' => $orderDetail]);
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
        if ($product) {
            $product->archived = true;
            $product->save();
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
    // not finish ang edit inventory ug update inventory yawa sigi rag cannot be found edi wow
    public function editInventory($product_id) 
    {
        $product = tblproduct::find($product_id);
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
            'id' => 'required|integer',
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
                $product->inventory->supplier_id = $request->editSupplierId; // Ensure correct column name

                // Save the updated inventory data
                $product->inventory->save();
            }

            // Redirect with success message
            return redirect()->back()->with('success', 'Inventory updated successfully.');
        }

        // If product not found, redirect with error message
        return redirect()->back()->with('error', 'Inventory not found.');
    }

}
