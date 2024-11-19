<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
</head>
<body>
    <h1>Create a New User</h1>

    <!-- Display Success Message -->
    @if (session('success'))
        <div style="color: green; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif


    <!-- Form to Create User -->
    <form action="{{ route('admin.storeUser') }}" method="POST">
        @csrf

        <!-- Name -->
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        @error('name')
            <div style="color: red;">{{ $message }}</div>
        @enderror
        <br><br>

        <!-- Email -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <div style="color: red;">{{ $message }}</div>
        @enderror
        <br><br>

        <!-- Username -->
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="{{ old('username') }}" required>
        @error('username')
            <div style="color: red;">{{ $message }}</div>
        @enderror
        <br><br>

        <!-- Password -->
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        @error('password')
            <div style="color: red;">{{ $message }}</div>
        @enderror
        <br><br>

        <!-- Confirm Password -->
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
        <br><br>

        <!-- Visibility -->
        <label for="visibility">Visibility:</label>
        <select id="visibility" name="visibility" required>
            <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public</option>
            <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private</option>
        </select>
        @error('visibility')
            <div style="color: red;">{{ $message }}</div>
        @enderror
        <br><br>

        <!-- Account Status -->
        <label for="account_status">Account Status:</label>
        <select id="account_status" name="account_status" required>
            <option value="active" {{ old('account_status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="offline" {{ old('account_status') == 'offline' ? 'selected' : '' }}>Offline</option>
            <option value="deleted" {{ old('account_status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
            <option value="blocked" {{ old('account_status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
        </select>
        @error('account_status')
            <div style="color: red;">{{ $message }}</div>
        @enderror
        <br><br>

        <!-- Submit Button -->
        <button type="submit">Create User</button>
    </form>
</body>
</html>
