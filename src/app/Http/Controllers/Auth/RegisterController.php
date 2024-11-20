<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Display the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:user,email', // Adjusted for table name
            'password' => 'required|min:8|confirmed',
        ]);

        // Create the user
        $user = User::create([
            'username' => $request->name, // Assuming username = name
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'visibility' => 'public',
            'account_status' => 'active',
            'last_login' => now(),
        ]);

        // Log in the user
        Auth::login($user);

        // Regenerate session to prevent session fixation attacks
        $request->session()->regenerate();

        // Log the successful registration
        Log::info('New user registered and logged in:', ['email' => $user->email]);

        // Redirect to the home page
        return redirect()->route('home')
            ->with('success', 'You have successfully registered and logged in!');
    }
}
