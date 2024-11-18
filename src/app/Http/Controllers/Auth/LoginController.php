<?php
 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Display a login form.
     */
    public function showLoginForm()
    {
        // Redirect authenticated users to their dashboard or cards
        if (Auth::check()) {
            return redirect()->route('home');
        }

        // Show login form to unauthenticated users
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        // Validate user input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to log in the user with "remember me" option
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            // Log successful login
            Log::info('User logged in:', ['email' => $credentials['email']]);

            // Redirect to the intended page or default to cards
            return redirect()->intended('/cards');
        }

        // Log failed login attempt
        Log::warning('Failed login attempt:', ['email' => $credentials['email']]);

        // Redirect back with error messages
        return back()
            ->withErrors([
                'email' => 'The provided credentials are incorrect. Please try again.',
            ])
            ->onlyInput('email');
    }

    /**
     * Log out the user from the application.
     */
    public function logout(Request $request)
    {
        // Log the user out
        Auth::logout();

        // Invalidate the session to ensure it cannot be reused
        $request->session()->invalidate();

        // Regenerate the session token to protect against CSRF
        $request->session()->regenerateToken();

        // Log successful logout
        Log::info('User logged out successfully.');

        // Redirect to login page with success message
        return redirect()->route('login')
            ->with('success', 'You have logged out successfully!');
    }
}
