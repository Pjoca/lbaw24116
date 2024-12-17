<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Group;
use App\Models\Post;
use App\Models\Comment;

class SearchController extends Controller
{
    /**
     * General search for users, groups, posts, and comments.
     */
    public function search(Request $request)
    {
        // Retrieve the search inputs
        $query = $request->input('query');
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Initialize collections for each entity
        $users = collect();
        $groups = collect();
        $posts = collect();
        $comments = collect();

        // If there is a query, search for the specific type
        if (!empty($query)) {
            // Search for users if 'users' type is selected
            if ($type === 'users') {
                $users = User::where(function ($queryBuilder) use ($query) {
                    // Searching by name using full-text search
                    $queryBuilder->whereRaw("
                        to_tsvector('portuguese', name) || to_tsvector('english', name) @@ websearch_to_tsquery('portuguese', ?)
                        OR to_tsvector('portuguese', name) || to_tsvector('english', name) @@ websearch_to_tsquery('english', ?)
                    ", [$query, $query]);

                    // Fallback to ILIKE for additional matching
                    $queryBuilder->orWhere('name', 'ILIKE', '%' . $query . '%');
                })
                ->when(!Auth::check(), fn($q) => $q->where('visibility', 'public')) // If not logged in, only show public users
                ->orderByRaw("ts_rank(to_tsvector('portuguese', name) || to_tsvector('english', name), websearch_to_tsquery(?)) DESC", [$query])
                ->paginate(8);

            }
            // Search for groups if 'groups' type is selected
            elseif ($type === 'groups') {
                $groups = Group::where(function ($queryBuilder) use ($query) {
                    // Searching by name using full-text search
                    $queryBuilder->whereRaw("
                        to_tsvector('portuguese', name) || to_tsvector('english', name) @@ websearch_to_tsquery('portuguese', ?)
                        OR to_tsvector('portuguese', name) || to_tsvector('english', name) @@ websearch_to_tsquery('english', ?)
                    ", [$query, $query]);

                    // Fallback to ILIKE for additional matching
                    $queryBuilder->orWhere('name', 'ILIKE', '%' . $query . '%');
                })
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate . ' 00:00:00')) // Filter by start date
                ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate . ' 23:59:59')) // Filter by end date
                ->when(!Auth::check(), fn($q) => $q->where('visibility', 'public')) // If not logged in, only show public groups
                ->orderByRaw("ts_rank(to_tsvector('portuguese', name) || to_tsvector('english', name), websearch_to_tsquery(?)) DESC", [$query])
                ->paginate(8);
            }
            // Search for posts if 'posts' type is selected
            elseif ($type === 'posts') {
                $posts = Post::where(function ($queryBuilder) use ($query) {
                    // Searching by content using full-text search
                    $queryBuilder->whereRaw("
                        to_tsvector('portuguese', content) || to_tsvector('english', content) @@ websearch_to_tsquery('portuguese', ?)
                        OR to_tsvector('portuguese', content) || to_tsvector('english', content) @@ websearch_to_tsquery('english', ?)
                    ", [$query, $query]);

                    // Fallback to ILIKE for additional matching
                    $queryBuilder->orWhere('content', 'ILIKE', '%' . $query . '%');
                })
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate . ' 00:00:00')) // Filter by start date
                ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate . ' 23:59:59')) // Filter by end date
                ->when(!Auth::check(), fn($q) => $q->where('visibility', 'public')) // If not logged in, only show public posts
                ->orderByRaw("ts_rank(to_tsvector('portuguese', content) || to_tsvector('english', content), plainto_tsquery(?)) DESC", [$query])
                ->paginate(3);
            }
            // Search for comments if 'comments' type is selected
            elseif ($type === 'comments') {
                $comments = Comment::where(function ($queryBuilder) use ($query) {
                    // Searching by content using full-text search
                    $queryBuilder->whereRaw("
                        to_tsvector('portuguese', content) || to_tsvector('english', content) @@ websearch_to_tsquery('portuguese', ?)
                        OR to_tsvector('portuguese', content) || to_tsvector('english', content) @@ websearch_to_tsquery('english', ?)
                    ", [$query, $query]);

                    // Fallback to ILIKE for additional matching
                    $queryBuilder->orWhere('content', 'ILIKE', '%' . $query . '%');
                })
                ->when(!Auth::check(), fn($q) => $q->where('visibility', 'public')) // If not logged in, only show public comments
                ->orderByRaw("ts_rank(to_tsvector('portuguese', content) || to_tsvector('english', content), websearch_to_tsquery(?)) DESC", [$query])
                ->paginate(3);
            }
        }

        // Return the search results view
        return view('search.results', compact('query', 'type', 'startDate', 'endDate', 'users', 'groups', 'posts', 'comments'));
    }

    /**
     * Specific search for users.
     */
    public function searchUsers(Request $request)
    {
        $query = $request->input('query', '');

        $users = User::when(!empty($query), function ($q) use ($query) {
            $q->whereRaw("
                to_tsvector('portuguese', name) || to_tsvector('english', name) @@ websearch_to_tsquery('portuguese', ?) 
                OR to_tsvector('portuguese', name) || to_tsvector('english', name) @@ websearch_to_tsquery('english', ?)
            ", [$query, $query]);
        })
        ->when(!Auth::check(), fn($q) => $q->where('visibility', 'public')) // Only show public users if not logged in
        ->orderByRaw("ts_rank(to_tsvector('portuguese', name) || to_tsvector('english', name), websearch_to_tsquery(?)) DESC", [$query])
        ->get();

        return view('search.results', compact('users', 'query'));
    }

    /**
     * Specific search for groups.
     */
    public function searchGroups(Request $request)
    {
        $query = $request->input('query', '');

        $groups = Group::when(!empty($query), function ($q) use ($query) {
            $q->whereRaw("
                to_tsvector('portuguese', name) || to_tsvector('english', name) @@ websearch_to_tsquery('portuguese', ?) 
                OR to_tsvector('portuguese', name) || to_tsvector('english', name) @@ websearch_to_tsquery('english', ?)
            ", [$query, $query]);
        })
        ->orderByRaw("ts_rank(to_tsvector('portuguese', name) || to_tsvector('english', name), websearch_to_tsquery(?)) DESC", [$query])
        ->get();

        return view('search.results', compact('groups', 'query'));
    }

    /**
     * Specific search for posts.
     */
    public function searchPosts(Request $request)
    {
        $query = $request->input('query', '');

        $posts = Post::when(!empty($query), function ($q) use ($query) {
            $q->whereRaw("
                to_tsvector('portuguese', content) || to_tsvector('english', content) @@ websearch_to_tsquery('portuguese', ?) 
                OR to_tsvector('portuguese', content) || to_tsvector('english', content) @@ websearch_to_tsquery('english', ?)
            ", [$query, $query]);
        })
        ->orderByRaw("ts_rank(to_tsvector('portuguese', content) || to_tsvector('english', content), websearch_to_tsquery(?)) DESC", [$query])
        ->get();

        return view('search.results', compact('posts', 'query'));
    }

    /**
     * Specific search for comments.
     */
    public function searchComments(Request $request)
    {
        $query = $request->input('query', '');

        $comments = Comment::when(!empty($query), function ($q) use ($query) {
            $q->whereRaw("
                to_tsvector('portuguese', content) || to_tsvector('english', content) @@ websearch_to_tsquery('portuguese', ?) 
                OR to_tsvector('portuguese', content) || to_tsvector('english', content) @@ websearch_to_tsquery('english', ?)
            ", [$query, $query]);
        })
        ->orderByRaw("ts_rank(to_tsvector('portuguese', content) || to_tsvector('english', content), websearch_to_tsquery(?)) DESC", [$query])
        ->get();

        return view('search.results', compact('comments', 'query'));
    }
}
