<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function dashboard(){ 
        return view('user.dashboard');
    }
    public function order(){ 
        return view('user.order');
    }
    public function reservation(){ 
        return view('user.reservation');
    }
    public function service(){ 
        return view('user.service');
    }
}
