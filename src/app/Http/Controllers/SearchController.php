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
use App\Models\Comment;

class SearchController extends Controller
{
    /**
     * Handle search based on type (users, groups, posts, comments).
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Default empty collections
        $users = collect();
        $groups = collect();
        $posts = collect();
        $comments = collect();
    
        if (!empty($query)) {
            if ($type === 'users') {
                $users = User::where('name', 'LIKE', '%' . $query . '%')->get();
            } elseif ($type === 'groups') {
                $groups = Group::where('name', 'LIKE', '%' . $query . '%')->get();
            } elseif ($type === 'posts') {
                // If a date is provided
                $posts = Post::query()
                    ->where('content', 'LIKE', '%' . $query . '%')
                    ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate . ' 00:00:00'))
                    ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate . ' 23:59:59'))
                    ->orderBy('created_at', 'desc')
                    ->get();
            } elseif ($type === 'comments') {
                $comments = Comment::where('content', 'LIKE', '%' . $query . '%')->get();
            }
        } else {
            // Apply default date filtering for posts if no query is provided
            if ($type === 'posts') {
                $posts = Post::query()
                    ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate . ' 00:00:00'))
                    ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate . ' 23:59:59'))
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }
    
        return view('search.results', compact('query', 'users', 'groups', 'posts', 'comments'));
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


    public function searchGroups(Request $request)
    {
        $query = $request->input('query'); // Capture query input
        $type = $request->input('type');
        //$limit = $request->input('limit', null);
    
        // If no query is provided, return an empty collection
        if (empty($query)) {
            $groups = collect(); // Empty collection
        } 
        else {
            $groups = Group::select('*')
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
    
            $groups->orderBy('priority')
                  ->orderBy('name');
    
            /*if ($limit) {
                $users = $users->take($limit);
            }*/
    
            $groups = $groups->get();
        }
    
        /*if ($request->ajax()) {
            // Return the partial only
            return response(view('partials.search-results', compact('users'))->render());
        }*/
    
        return view('search.results', compact('groups', 'query'));
    }

    public function searchPosts(Request $request)
    {
        $query = $request->input('query');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Initialize query builder
        $posts = Post::query();
    
        // Filter by content query and date range
        if (!empty($query)) {
            $posts->where(function ($q) use ($query, $startDate, $endDate) {
                $q->where(function ($q2) use ($query) {
                    $q2->whereRaw('LOWER(content) = ?', [strtolower($query)])
                       ->orWhereRaw('LOWER(content) LIKE ?', [strtolower($query) . '%'])
                       ->orWhereRaw('LOWER(content) LIKE ?', ['%' . strtolower($query) . '%']);
                });
    
                // Add date filtering using whereRaw
                if (!empty($startDate) && !empty($endDate)) {
                    $q->whereRaw('created_at BETWEEN ? AND ?', [
                        $startDate . ' 00:00:00', 
                        $endDate . ' 23:59:59'
                    ]);
                } elseif (!empty($startDate)) {
                    $q->whereRaw('created_at >= ?', [$startDate . ' 00:00:00']);
                } elseif (!empty($endDate)) {
                    $q->whereRaw('created_at <= ?', [$endDate . ' 23:59:59']);
                }
            });
        } else {
            // Apply date filtering if no query is present
            if (!empty($startDate) && !empty($endDate)) {
                $posts->whereRaw('created_at BETWEEN ? AND ?', [
                    $startDate . ' 00:00:00', 
                    $endDate . ' 23:59:59'
                ]);
            } elseif (!empty($startDate)) {
                $posts->whereRaw('created_at >= ?', [$startDate . ' 00:00:00']);
            } elseif (!empty($endDate)) {
                $posts->whereRaw('created_at <= ?', [$endDate . ' 23:59:59']);
            }
        }
    
        // Add visibility condition for guest users
        if (!Auth::check()) {
            $posts->whereRaw('visibility = ?', ['public']);
        }
    
        // Retrieve results with descending order by date
        $posts = $posts->orderBy('created_at', 'desc')->get();
    
        // Return the view with results
        return view('search.results', [
            'posts' => $posts,
            'query' => $query,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }          

    public function searchComments(Request $request)
    {
        $query = $request->input('query'); // Capture query input
        $type = $request->input('type');
        //$limit = $request->input('limit', null);
    
        // If no query is provided, return an empty collection
        if (empty($query)) {
            $comments = collect(); // Empty collection
        } 
        else {
            $comments = Group::select('*')
                ->selectRaw('
                    CASE
                        WHEN LOWER(content) = ? THEN 1
                        WHEN LOWER(content) LIKE ? THEN 2
                        WHEN LOWER(content) LIKE ? THEN 3
                        ELSE 4
                    END AS priority
                ', [
                    strtolower($query),
                    strtolower($query) . '%',
                    '%' . strtolower($query) . '%',
                ])
                ->where(function ($q) use ($query) {
                    $q->whereRaw('LOWER(content) = ?', [strtolower($query)])
                      ->orWhereRaw('LOWER(content) LIKE ?', [strtolower($query) . '%'])
                      ->orWhereRaw('LOWER(content) LIKE ?', ['%' . strtolower($query) . '%']);
                });
    
            // Add visibility condition for guest users
            if (!Auth::check()) {
                $users->where('visibility', 'public');
            }
    
            $comments->orderBy('priority')
                  ->orderBy('name');
    
            /*if ($limit) {
                $users = $users->take($limit);
            }*/
    
            $comments = $comments->get();
        }
    
        /*if ($request->ajax()) {
            // Return the partial only
            return response(view('partials.search-results', compact('users'))->render());
        }*/
    
        return view('search.results', compact('comments', 'query'));
    }
}
