<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth/register');
    }

    public function registerSave(Request $request)
    {
        $request->validate([
            'fullname' => ['required', function ($attribute, $value, $fail) {
                if (User::where('fullname', $value)->exists()) {
                    $fail('The fullname has already been registered.');
                }
            }],
            'username' => ['required', 'unique:user,username'],
            'jobtitle' => ['required'],
            'user_contact' => ['required'],
            'password' => ['required', 'min:8'],
        ]);

        try {
            User::create([
                'fullname' => $request->fullname,
                'username' => $request->username,
                'jobtitle' => $request->jobtitle,
                'user_contact' => $request->user_contact,
                'password' => Hash::make($request->password),
            ]);
            
            $this->showAlert('success', 'Registration successful', 'Please log in.');
            return redirect()->back();
        } catch (\Exception $e) {
            $this->showAlert('error', 'Registration failed', 'Please try again.');
            return redirect()->back();
        }
    }


    public function login()
    {
        return view('auth/login');
    }

    public function loginSave(Request $request)
    {
        try {
            $request->validate([
                'username' => ['required', 'string'],
                'password' => ['required', 'min:8'],
            ]);

            $user = User::where('username', $request->username)->first();

            if (!Hash::check($request->password, $user->password)) {
                $this->showAlert('error', 'Error!', 'Username or password is incorrect. Please try again.');
                return back();
            }
            $jobtitle = $user->jobtitle;

            if ($jobtitle === 'admin') {
                return redirect()->route('adminDashboard');
            } elseif ($jobtitle === 'helper' || $jobtitle === 'staff') {
                return redirect()->route('userDashboard');
            } else {
                $this->showAlert('error', 'Error!', 'Unauthorized access.');
                return back();
            }
        } catch (\Exception $e) {
            Log::error("Login Error", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->showAlert('error', 'Error!', 'An unexpected error occurred. Please try again later.');
            return back();
        }
}

    public function logout(){

        if(Session::has('user_ID')){
            Session::pull('user_ID');
        }
        return redirect()->route('login');
    }
    public static function showAlert($icon, $title, $text) {
        Session::flash('alertShow', true);
        Session::flash('icon', $icon);
        Session::flash('title', $title);
        Session::flash('text', $text);
    }
}
