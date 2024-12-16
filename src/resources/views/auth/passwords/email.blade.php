@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center">
    <!-- Centered Card for Reset Password -->
    <div class="w-full max-w-md bg-white shadow-2xl rounded-lg p-8">
        
        <!-- Page Title -->
        <h2 class="text-3xl font-bold text-center text-teal-600 mb-6">Reset Password</h2>

        <!-- Success Message -->
        @if (session('status'))
            <div class="bg-green-100 text-green-800 text-center font-semibold py-2 px-4 rounded-lg mb-6">
                {{ session('status') }}
            </div>
        @endif

        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Input Field -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent" 
                    placeholder="Enter your email" 
                    required
                >
                @error('email')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div>
                <button 
                    type="submit" 
                    class="w-full bg-teal-500 hover:bg-teal-600 text-white font-bold py-3 rounded-lg shadow-lg transition duration-300">
                    Send Password Reset Link
                </button>
            </div>
        </form>

        <!-- Back to Login Link -->
        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-teal-500 hover:underline text-sm font-medium">
                Remembered your password? Login
            </a>
        </div>
    </div>
</div>
@endsection
