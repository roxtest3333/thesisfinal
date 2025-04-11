<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role; 

class AdminRegisterController extends Controller
{
    

    public function showRegistrationForm()
    {
        return view('auth.admin-register');
    }

    public function register(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'name' => 'required',
            'faculty_id' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'faculty_id' => $request->faculty_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => 1, 
        ]);

        // Assign the 'admin' role to the new user
        $user->assignRole('admin');

        return redirect('/admin/dashboard')->with('message', 'Admin registered successfully.');
    }
}
