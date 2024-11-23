@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Edit Profile</h2>

        <!-- Display Success Message -->
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Display Errors -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('profile.update', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                       class="w-full border border-gray-300 rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500" required>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                       class="w-full border border-gray-300 rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500" required>
            </div>

            <!-- Username -->
            <div class="mb-4">
                <label for="username" class="block font-bold mb-2">Username:</label>
                <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" 
                       class="w-full border border-gray-300 rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500" required>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block font-bold mb-2">New Password (Optional):</label>
                    <input type="password" id="password" name="password" 
                           class="w-full border border-gray-300 rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500">
            </div>

            <!-- Profile Image -->
            <div class="mb-4">
                <label for="image" class="block font-bold mb-2">Profile Image:</label>
                <input type="file" id="image" name="image" class="w-full">
                @if ($user->image_url)
                    <img src="{{ asset('storage/' . $user->image_url) }}" alt="Current Profile Image" 
                         class="mt-2 w-24 h-24 rounded-full">
                @endif
            </div>

            <!-- Visibility -->
            <div class="mb-4">
                <label for="visibility" class="block font-bold mb-2">Visibility:</label>
                <select id="visibility" name="visibility" 
                        class="w-full border border-gray-300 rounded p-2 focus:ring focus:ring-teal-500 focus:border-teal-500" required>
                    <option value="public" {{ old('visibility', $user->visibility) == 'public' ? 'selected' : '' }}>Public</option>
                    <option value="private" {{ old('visibility', $user->visibility) == 'private' ? 'selected' : '' }}>Private</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="text-right">
                <button type="submit" class="bg-teal-500 text-white py-2 px-4 rounded hover:bg-teal-600 transition">
                    Save Changes
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
