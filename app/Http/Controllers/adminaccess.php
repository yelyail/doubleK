<?php

namespace App\Http\Controllers;

use App\Models\tblinventory;
use App\Models\User;
use App\Models\tblcustomer;
use App\Models\tblservice;
use App\Models\tblsupplier;
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
        $suppliers = tblsupplier::with([
                                    'tblinventory.tblproduct',         
                                    'tblorderdetails.user'              
                                ])->get();
                                
        return view('admin.supplier', compact('suppliers'));
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
    public function storeSupplier(Request $request){
        $validated = $request->validate([
            'supplierName' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'pNum' => 'required|string|max:255',
            'landline' => 'required|string',
            'address' => 'required|string',
        ]);
        try {
            tblsupplier::create([
                'supplier_name' => $request->supplierName,
                'supplier_contact' => $request->email,
                'supplier_landline' => $request->pNum,
                'supplier_address' => $request->landline,
                'supplier_email' => $request->address,
                
            ]);
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back();
        }
        
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

    

}
