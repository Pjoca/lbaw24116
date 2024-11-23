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
    
        // Check if the authenticated user is viewing their own profile
        $isOwnProfile = Auth::id() === $user->id;
    
        // Prepare the user data for the response
        $userData = [
            'id' => $user->id,
            'username'=> $user->username,
            'name' => $user->name,
            'last_login' => $user->last_login,

        ];
    
        // Include email if it's the authenticated user's own profile
        if ($isOwnProfile) {
            $userData['email'] = $user->email;
        }
    
        // Return the user profile
        return view('pages.profile', [
            'user' => $userData,
        ]);
    }

    /**
     * Search users.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
    
        $users = User::select('*')
            ->selectRaw('
                CASE
                    WHEN LOWER(name) = ? THEN 1                         -- Exact match
                    WHEN LOWER(name) LIKE ? THEN 2                      -- Starts with query
                    WHEN LOWER(name) LIKE ? THEN 3                      -- Contains query
                    ELSE 4                                              -- No match
                END AS priority
            ', [
                strtolower($query),
                strtolower($query) . '%', // Starts with query
                '%' . strtolower($query) . '%', // Contains query
            ])
            ->where(function ($q) use ($query) {
                $q->whereRaw('LOWER(name) = ?', [strtolower($query)])
                  ->orWhereRaw('LOWER(name) LIKE ?', [strtolower($query) . '%'])
                  ->orWhereRaw('LOWER(name) LIKE ?', ['%' . strtolower($query) . '%']);
            })
            ->orderBy('priority')
            ->orderBy('name') // Secondary ordering by name alphabetically
            ->get();
    
        return view('search.results', compact('users', 'query'));
    }
    
    

    /*public function search(Request $request)
    {
        $query = $request->input('query');
    
        // Fetch exact matches
        $exactMatches = User::where('name', $query)->get();
    
        // Fetch partial matches excluding exact matches
        $partialMatches = User::where('name', 'ILIKE', "%$query%")
                              ->whereNotIn('id', $exactMatches) // Exclude exact matches
                              ->get();
    
        // Combine exact and partial matches with ordering
        $users = User::select('*')
                     ->selectRaw('
                         CASE
                             WHEN LOWER(name) = ? THEN 1
                             ELSE 2
                         END AS priority
                     ', [strtolower($query)])
                     ->orderBy('priority')
                     ->orderBy('name')
                     ->get();
    
        return view('search.results', compact('users', 'query'));
    }*/
    


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
