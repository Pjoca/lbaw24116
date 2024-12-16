@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center">
    <div class="w-full max-w-md bg-white shadow-2xl rounded-lg p-8">
        <h2 class="text-3xl font-bold text-center text-teal-600 mb-6">Reset Password</h2>

        <!-- Status Message -->
        @if (session('status'))
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded-md">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent" 
                    value="{{ old('email', $email) }}" 
                    required 
                    autofocus
                >
                @error('email')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                <input 
                    type="password" 
                    name="password" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent" 
                    required
                >
                @error('password')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm New Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent" 
                    required
                >
                @error('password_confirmation')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div>
                <button 
                    type="submit" 
                    class="w-full bg-teal-500 text-white font-bold py-3 rounded-lg shadow-md hover:bg-teal-600 transition duration-300">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
