<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Retrieve the profile information of a user.
     */
    public function show($id)
    {
        // Find the user by ID or return a 404 response
        $user = User::findOrFail($id);

        // Authorize viewing the profile
        $this->authorize('view', $user);

        // Return the user profile
        return response()->json([
            'message' => 'Profile retrieved successfully',
            'data' => $user,
        ], 200);
    }

    /**
     * Update the profile information of the authenticated user.
     */
    public function update(Request $request, $id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Ensure the authenticated user is updating their own profile
        $this->authorize('update', $user);

        // Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'visibility' => 'required|in:public,private',
        ]);

        // Update the user's profile
        $user->update($validated);

        // Return success response
        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $user,
        ], 200);
    }
}
