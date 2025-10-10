<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            // Return proper JSON with status 422
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

            // Send welcome mail
            $data = ['name' => $user->name];
            Mail::to($user->email)->send(new WelcomeMail($data));



        return response()->json(['success' => 'Registration successful. You can now login!']);
    }

    // Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['success' => 'Login successful']);
        }

        return response()->json(['errors' => ['email' => ['Invalid credentials']]], 422);
    }

    public function loginForm() {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('login');
    }
    
    public function registerForm() {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('register');
    }
    
    // Dashboard (after login)
    public function dashboard() {
        return view('dashboard');
    }

    // Logout
    public function logout() {
        Auth::logout();
        return redirect()->route('login.form');
    }
}
