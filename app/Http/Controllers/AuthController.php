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
            'fullname' => ['required'],
            'username' => ['required', 'unique:user'],
            'jobtype' => ['required'],
            'user_contact' => ['required'],
            'password' => ['required', 'min:8'],
        ]);

        try {
            $user = User::create([
                'fullname' => $request->fullname,
                'username' => $request->username,
                'jobtype' => $request->jobtype,
                'user_contact' => $request->user_contact,
                'password' => Hash::make($request->password),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Registration failed. Please try again.');
        }
        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
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
            $jobType = $user->jobtype;

            if ($jobType === 1) {
                return redirect()->route('adminDashboard');
            } elseif ($jobType === 0) {
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
