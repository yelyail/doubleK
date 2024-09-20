<?php

namespace App\Http\Controllers;

use App\Models\tblinventory;
use App\Models\User;
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
    public function adminSupplier(){ 
        return view('admin.supplier');
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

    //for the progress \
    public function custInfo(){
        return view('admin.custInfo');
    }
    public function confirm(){
        return view('admin.confirm');
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
