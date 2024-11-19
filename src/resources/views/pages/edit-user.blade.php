<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User: {{ $user->name }}</h1>

    <!-- Display Success Message -->
    @if (session('success'))
        <div style="color: green; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display Errors -->
    @if ($errors->any())
        <div style="color: red; margin-bottom: 20px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form to Edit User -->
    <form action="{{ route('admin.updateUser', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Name -->
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="{{ $user->name }}" required>
        <br><br>

        <!-- Email -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ $user->email }}" required>
        <br><br>

        <!-- Username -->
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="{{ $user->username }}" required>
        <br><br>

        <!-- Visibility -->
        <label for="visibility">Visibility:</label>
        <select id="visibility" name="visibility" required>
            <option value="public" {{ $user->visibility == 'public' ? 'selected' : '' }}>Public</option>
            <option value="private" {{ $user->visibility == 'private' ? 'selected' : '' }}>Private</option>
        </select>
        <br><br>

        <!-- Account Status -->
        <label for="account_status">Account Status:</label>
        <select id="account_status" name="account_status" required>
            <option value="active" {{ $user->account_status == 'active' ? 'selected' : '' }}>Active</option>
            <option value="offline" {{ $user->account_status == 'offline' ? 'selected' : '' }}>Offline</option>
            <option value="deleted" {{ $user->account_status == 'deleted' ? 'selected' : '' }}>Deleted</option>
            <option value="blocked" {{ $user->account_status == 'blocked' ? 'selected' : '' }}>Blocked</option>
        </select>
        <br><br>

        <!-- Submit Button -->
        <button type="submit">Update User</button>
    </form>
</body>
</html>
