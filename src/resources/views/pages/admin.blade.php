@extends('layouts.app')

@section('content')

<!-- Flash Messages -->
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-4">
    <input 
        type="text" 
        id="user-search-input" 
        placeholder="Search users..." 
        class="w-full border border-gray-300 rounded p-2"
    >
</div>

<div class="user-list mb-4">
    <h2 class="text-2xl font-semibold mb-4">User List</h2>
    <div class="overflow-y-auto max-h-80 border border-gray-300 rounded shadow-md">
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="border p-2">ID</th>
                    <th class="border p-2">Name</th>
                    <th class="border p-2">Email</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody id="user-list-tbody">
                @foreach ($users as $user)
                    <tr>
                        <td class="border p-2">{{ $user->id }}</td>
                        <td class="border p-2">{{ $user->name }}</td>
                        <td class="border p-2">{{ $user->email }}</td>
                        <td class="border p-2">
                            <button 
                                onclick="showEditUserModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->username }}')" 
                                class="text-green-500 hover:underline">
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Create User Section -->
<div id="create-user" class="bg-gray-100 p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">Create User</h2>
    <form action="{{ route('admin.storeUser') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="create-name" class="block text-sm font-medium">Name</label>
            <input type="text" name="name" id="create-name" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label for="create-email" class="block text-sm font-medium">Email</label>
            <input type="email" name="email" id="create-email" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label for="create-username" class="block text-sm font-medium">Username</label>
            <input type="text" name="username" id="create-username" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label for="create-password" class="block text-sm font-medium">Password</label>
            <input type="password" name="password" id="create-password" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label for="create-password_confirmation" class="block text-sm font-medium">Confirm Password</label>
            <input type="password" name="password_confirmation" id="create-password_confirmation" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label for="create-visibility" class="block text-sm font-medium">Visibility</label>
            <select name="visibility" id="create-visibility" class="w-full border rounded p-2" required>
                <option value="public">Public</option>
                <option value="private">Private</option>
            </select>
        </div>
        <div>
            <label for="create-account-status" class="block text-sm font-medium">Account Status</label>
            <select name="account_status" id="create-account-status" class="w-full border rounded p-2" required>
                <option value="active">Active</option>
                <option value="offline">Offline</option>
                <option value="deleted">Deleted</option>
                <option value="blocked">Blocked</option>
            </select>
        </div>
        <button type="submit" class="bg-teal-500 text-white py-2 px-4 rounded hover:bg-teal-600 transition">
            Create
        </button>
    </form>
</div>

    <!-- Edit User Modal -->
    <div id="edit-user-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4">Edit User</h2>
            <form id="edit-user-form" method="POST" action="">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit-name" class="block text-sm font-medium">Name</label>
                    <input type="text" name="name" id="edit-name" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label for="edit-email" class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" id="edit-email" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label for="edit-username" class="block text-sm font-medium">Username</label>
                    <input type="text" name="username" id="edit-username" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label for="edit-visibility" class="block text-sm font-medium">Visibility</label>
                    <select name="visibility" id="edit-visibility" class="w-full border rounded p-2" required>
                        <option value="public">Public</option>
                        <option value="private">Private</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="edit-account-status" class="block text-sm font-medium">Account Status</label>
                    <select name="account_status" id="edit-account-status" class="w-full border rounded p-2" required>
                        <option value="active">Active</option>
                        <option value="offline">Offline</option>
                        <option value="deleted">Deleted</option>
                        <option value="blocked">Blocked</option>
                    </select>
                </div>
                <button type="submit" class="bg-teal-500 text-white py-2 px-4 rounded hover:bg-teal-600 transition">
                    Save Changes
                </button>
            </form>
            <button onclick="hideEditUserModal()" class="mt-4 text-gray-600 underline">Cancel</button>
        </div>
    </div>
</div>
@endsection
