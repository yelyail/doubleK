<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
        public function register(){
            return view('auth/register');
        }
        public function registerSave(Request $request){
            Validator::make($request->all(), [
                'fullname' => 'required',
                'username' => 'required',
                'jobtype' => 'required',
                'user_contact' => 'required',
                'password' => 'required'
            ])->validate();
            User::create([
                'fullname' => $request->fullname,
                'username' => $request->username,
                'jobtype' => $request->jobtype,
                'user_contact' => $request->user_contact,
                'password' => Hash::make(($request->password)),
                'type' => "0"
            ]);
            
            return redirect()->route('login');
        }
        public function login(){
            return view('auth/login');
        }
        public function loginSave(Request $request)
        {
            Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required',
            ])->validate();

            if (!Auth::attempt($request->only('username', 'password'))) {
                Log::error('Login failed for user: ' . $request->username);
                throw ValidationException::withMessages([
                    'username' => trans('auth.failed'),
                ]);
            }
            Log::info('Attempting login for username: ' . $request->username);


            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->username === 'admin' && $user->jobtype === '0') {
                return redirect()->route('adminDashboard');
            } elseif (in_array($user->username, ['helper', 'staff']) && $user->jobtype === '1') {
                return redirect()->route('userDashboard');
            }

            return redirect()->route('login');
        }


}
