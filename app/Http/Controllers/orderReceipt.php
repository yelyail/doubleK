<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tblcustomer;
use App\Models\tblpaymentmethod;
use App\Models\tblorderitems;
use App\Models\tblorderreceipt;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF library
use Illuminate\Support\Facades\DB; // For transaction handling

class orderReceipt extends Controller
{
    public function storeReceipt(Request $request)
    {
    }
}
