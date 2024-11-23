<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function listUsers()
    {
        $this->authorize('general', Admin::class);

        $users = User::all();

        return view('pages.admin', compact('users'));
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
        $this->authorize('general', Admin::class);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email,' . $id,
            'username' => 'required|string|max:50|unique:user,username,' . $id,
            'visibility' => 'required|in:public,private',
            'account_status' => 'required|in:active,offline,deleted,blocked',
        ]);

        $user = User::findOrFail($id);
        $user->update($validated);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
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
        $this->authorize('general', Admin::class);

        try {
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
            $user = User::create($validated);

            return redirect()->route('admin.users')->with('success', 'User created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'An unexpected error occurred.');
        }
    }

}
