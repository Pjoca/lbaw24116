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
        $query = $request->input('query');
        $type = $request->input('type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $users = collect();
        $groups = collect();
        $posts = collect();
        $comments = collect();

        if (!empty($query)) {
            if ($type === 'users') {

                $users = User::where(function ($queryBuilder) use ($query) {
                    // Full-text search part: exact or stemmed matches using to_tsvector and websearch_to_tsquery
                    $queryBuilder->whereRaw("
                        to_tsvector('portuguese', name) || to_tsvector('english', name) @@ websearch_to_tsquery('portuguese', ?)
                        OR to_tsvector('portuguese', name) || to_tsvector('english', name) @@ websearch_to_tsquery('english', ?)
                    ", [$query, $query]);
                
                    // Partial match: case-insensitive substring search using ILIKE
                    $queryBuilder->orWhere('name', 'ILIKE', '%' . $query . '%');
                })
                ->when(!Auth::check(), fn($q) => $q->where('visibility', 'public'))
                ->orderByRaw("ts_rank(to_tsvector('portuguese', name) || to_tsvector('english', name), websearch_to_tsquery(?)) DESC", [$query])
                ->get();

                
                
            } elseif ($type === 'groups') {
                $groups = Group::whereRaw("
                    to_tsvector('portuguese', name) || to_tsvector('english', name) @@ websearch_to_tsquery('portuguese', ?) 
                    OR to_tsvector('portuguese', name) || to_tsvector('english', name) @@ websearch_to_tsquery('english', ?)
                ", [$query, $query])
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate . ' 00:00:00'))
                ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate . ' 23:59:59'))
                ->when(!Auth::check(), fn($q) => $q->where('visibility', 'public'))
                ->orderByRaw("ts_rank(to_tsvector('portuguese', name) || to_tsvector('english', name), websearch_to_tsquery(?)) DESC", [$query])
                ->get();
            } elseif ($type === 'posts') {
                $posts = Post::whereRaw("
                    to_tsvector('portuguese', content) || to_tsvector('english', content) @@ websearch_to_tsquery('portuguese', ?) 
                    OR to_tsvector('portuguese', content) || to_tsvector('english', content) @@ websearch_to_tsquery('english', ?)
                ", [$query, $query])
                ->when($startDate, fn($q) => $q->where('created_at', '>=', $startDate . ' 00:00:00'))
                ->when($endDate, fn($q) => $q->where('created_at', '<=', $endDate . ' 23:59:59'))
                ->when(!Auth::check(), fn($q) => $q->where('visibility', 'public'))
                ->orderByRaw("ts_rank(to_tsvector('portuguese', content) || to_tsvector('english', content), plainto_tsquery(?)) DESC", [$query])
                ->get();
            } elseif ($type === 'comments') {
                $comments = Comment::whereRaw("
                    to_tsvector('portuguese', content) || to_tsvector('english', content) @@ websearch_to_tsquery('portuguese', ?) 
                    OR to_tsvector('portuguese', content) || to_tsvector('english', content) @@ websearch_to_tsquery('english', ?)
                ", [$query, $query])
                ->when(!Auth::check(), fn($q) => $q->where('visibility', 'public'))
                ->orderByRaw("ts_rank(to_tsvector('portuguese', content) || to_tsvector('english', content), websearch_to_tsquery(?)) DESC", [$query])
                ->get();
            }
        }

        return view('search.results', compact('query', 'users', 'groups', 'posts', 'comments'));
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
        ->when(!Auth::check(), fn($q) => $q->where('visibility', 'public'))
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