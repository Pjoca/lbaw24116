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
    // Validar os campos de entrada
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Verificar se o email existe no banco de dados
    $user = \App\Models\User::where('email', $credentials['email'])->first();

    if (!$user) {
        // Se o email não existe
        return back()->withErrors([
            'email' => 'No account registered with that email.',
        ])->onlyInput('email');
    }

    // Verificar se a senha está correta
    if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $request->filled('remember'))) {
        // Se a senha estiver incorreta
        return back()->withErrors([
            'password' => 'Password is incorrect.',
        ])->onlyInput('email');
    }

    // Regenerar a sessão para evitar ataques de fixação de sessão
    $request->session()->regenerate();

    // Registrar login bem-sucedido
    \Log::info('User logged in:', ['email' => $credentials['email']]);

    // Redirecionar para a página pretendida ou padrão
    return redirect()->intended('/timeline/public');
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
