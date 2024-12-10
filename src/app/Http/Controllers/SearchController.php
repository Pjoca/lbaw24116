<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Group;
use App\Models\Post;

class SearchController extends Controller
{
    /**
     * Handle search based on type (users, groups, all).
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type');

        switch ($type) {
            case 'users':
                return $this->searchUsers($request);
            case 'groups':
                return $this->searchGroups($request);
            case 'posts':
                return $this->searchPosts($request);  
            case 'comments':
                return $this->searchComments($request);      
        }
    }

    public function searchUsers(Request $request)
    {
        $query = $request->input('query'); // Capture query input
        $type = $request->input('type');
        $limit = $request->input('limit', null);
    
        // If no query is provided, return an empty collection
        if (empty($query)) {
            $users = collect(); // Empty collection
        } 
        else {
            $users = User::select('*')
                ->selectRaw('
                    CASE
                        WHEN LOWER(name) = ? THEN 1
                        WHEN LOWER(name) LIKE ? THEN 2
                        WHEN LOWER(name) LIKE ? THEN 3
                        ELSE 4
                    END AS priority
                ', [
                    strtolower($query),
                    strtolower($query) . '%',
                    '%' . strtolower($query) . '%',
                ])
                ->where(function ($q) use ($query) {
                    $q->whereRaw('LOWER(name) = ?', [strtolower($query)])
                      ->orWhereRaw('LOWER(name) LIKE ?', [strtolower($query) . '%'])
                      ->orWhereRaw('LOWER(name) LIKE ?', ['%' . strtolower($query) . '%']);
                });
    
            // Add visibility condition for guest users
            if (!Auth::check()) {
                $users->where('visibility', 'public');
            }
    
            $users->orderBy('priority')
                  ->orderBy('name');
    
            if ($limit) {
                $users = $users->take($limit);
            }
    
            $users = $users->get();
        }
    
        if ($request->ajax()) {
            // Return the partial only
            return response(view('partials.search-results', compact('users'))->render());
        }
    
        // Fallback for non-AJAX requests
        return view('search.results', compact('users', 'query'));
    }
}
