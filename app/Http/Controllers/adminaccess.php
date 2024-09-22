<?php

namespace App\Http\Controllers;

use App\Models\tblinventory;
use App\Models\User;
use App\Models\tblcustomer;
use App\Models\tblservice;
use App\Models\tblsupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
    
    public function adminInventory(){ 
        return view('admin.inventory');
    }
    public function adminOrder(){ 
        return view('admin.order');
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
        return view('admin.service');
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
    public function storeProduct(Request $request){
        $inventory = new tblinventory;
        $inventory->name = $request->name;
        $inventory->description = $request->description;
        $inventory->price = $request->price;
        $inventory->quantity = $request->quantity;
        $inventory->save();
        return redirect()->back();
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
        $service->service_name = $request->service_name;
        $service->service_description = $request->service_description;
        $service->service_price = $request->service_price;
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
    
    public function confirm(){
        return view('admin.confirm');
    }
    //for editing the progress
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

            return response()->back()->with('success', 'Supplier updated successfully!');;
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

}
