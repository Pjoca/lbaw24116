<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function listUsers()
    {
        // Check if the current user can list all users.
        $this->authorize('general', Admin::class);

        // Get all users.
        $users = User::all();

        // Use the pages.users template to display all users.
        return response()->json($users);
    }

    public function showUser($id)
    {
        $this->authorize('general', Admin::class);
        $user = User::findOrFail($id);

        $user->makeHidden(['user_search_tsv']);

        return response()->json($user);
    }

    public function editUser($id)
    {
        // Authorize the action
        $this->authorize('general', Admin::class);
    
        // Find the user by ID or fail
        $user = User::findOrFail($id);
    
        // Return a view with the user's details
        return view('pages.edit-user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        // Authorize the action
        $this->authorize('general', Admin::class);

        // Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email,' . $id,
            'username' => 'required|string|max:50|unique:user,username,' . $id,
            'visibility' => 'required|in:public,private',
            'account_status' => 'required|in:active,offline,deleted,blocked',
        ]);

        // Find and update the user
        $user = User::findOrFail($id);
        $user->update($validated);

        // Redirect back to the edit page with a success message
        return redirect()->route('admin.editUser', ['id' => $id])->with('success', 'User updated successfully.');

    }

    public function createUser()
    {
        // Authorize the action
        $this->authorize('general', Admin::class);

        // Return the view for creating a user
        return view('pages.create-user');
    }

    public function storeUser(Request $request)
    {
        // Authorize the action
        $this->authorize('general', Admin::class);

        // Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:user,email',
            'username' => 'required|string|max:50|unique:user,username',
            'password' => 'required|string|min:8|confirmed',
            'visibility' => 'required|in:public,private',
            'account_status' => 'required|in:active,offline,deleted,blocked',
        ]);

        // Hash the password
        $validated['password'] = bcrypt($validated['password']);

        // Create the user
        try {
            // Create the user
            $user = User::create($request->all());
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'Email must be a FEUP institutional email')) {
                return back()->withErrors(['email' => 'The email must be a FEUP institutional email (@up.pt).']);
            }
        
            // Re-throw the exception if it's not related to the email
            throw $e;
        }
        

        // Redirect to the user list with a success message
        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

}
