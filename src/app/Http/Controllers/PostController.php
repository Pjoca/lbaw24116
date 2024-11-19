<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Retrieve the public timeline of posts. Access: PUB
     */
    public function publicTimeline()
    {
        $posts = Post::where('visibility', 'public')->latest()->get();

        return response()->json([
            'message' => 'Public timeline retrieved successfully',
            'data' => $posts,
        ], 200);
    }

    /**
     * Retrieve the personalized timeline for the authenticated user. Access: USR
     */
    /*
    public function personalizedTimeline()
    {
        $user = Auth::user();

        // Get public posts or private posts from friends
        $posts = Post::where('visibility', 'public')
            ->orWhere(function ($query) use ($user) {
                $query->where('visibility', 'private')
                      ->whereIn('user_id', $user->friends()->pluck('id')); // Assuming `friends()` returns friends
            })
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Personalized timeline retrieved successfully',
            'data' => $posts,
        ], 200);
    }
    */

    /**
     * Create a new post by an authenticated user. Access: USR
     */
    public function create(Request $request)
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'content' => 'required|string',
            'visibility' => 'required|in:public,private,group',
            'id_group' => 'nullable|integer|exists:groups,id',
        ]);

        $post = Post::create([
            'content' => $validated['content'],
            'visibility' => $validated['visibility'],
            'id_user' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'data' => $post,
        ], 201);
    }

    /**
     * Edit an existing post by the authenticated user. Access: OWN
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'content' => 'required|string',
            'visibility' => 'required|in:public,private,group',
        ]);

        $post->update($validated);

        return response()->json([
            'message' => 'Post updated successfully',
            'data' => $post,
        ], 200);
    }


    /**
     * Delete an existing post by the authenticated user. Access: OWN
     */
    public function delete(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully',
        ], 200);
    }
}
