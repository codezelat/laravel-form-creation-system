<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showLogin()
    {
        // Redirect if already authenticated
        if (Session::get('admin_authenticated')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // Check credentials against environment variables
        $adminUsername = env('ADMIN_USERNAME');
        $adminPassword = env('ADMIN_PASSWORD');

        if ($username === $adminUsername && $password === $adminPassword) {
            // Set session
            Session::put('admin_authenticated', true);
            Session::put('admin_username', $username);

            return redirect()->route('admin.dashboard')->with('success', 'Welcome to admin panel!');
        }

        return back()->withErrors([
            'credentials' => 'Invalid username or password.',
        ])->withInput($request->only('username'));
    }

    /**
     * Handle admin logout
     */
    public function logout()
    {
        Session::forget('admin_authenticated');
        Session::forget('admin_username');
        Session::flush();

        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $adminUsername = Session::get('admin_username');
        return view('admin.dashboard', compact('adminUsername'));
    }
}
