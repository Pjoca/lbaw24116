<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function publicTimeline(Request $request)
    {
        $query = Post::where('visibility', 'public')->latest();

        if ($request->ajax()) {
            $posts = $query->simplePaginate(5);
            return view('partials.posts-renderer', compact('posts'))->render();
        }

        $posts = $query->simplePaginate(5);

        return view('pages.timeline', compact('posts'));
    }

    public function personalizedTimeline(Request $request)
    {
        $user = Auth::user();
        $friendIds = $user->friendships()->pluck('id_user2')->toArray();

        $query = Post::where('visibility', 'public')
            ->orWhere(function ($query) use ($user, $friendIds) {
                $query->where('visibility', 'private')
                    ->where(function ($subQuery) use ($user, $friendIds) {
                        $subQuery->whereIn('id_user', $friendIds)
                                ->orWhere('id_user', $user->id);
                    });
            })
            ->orWhere('id_user', $user->id)
            ->latest();

        if ($request->ajax()) {
            $posts = $query->simplePaginate(5);
            return view('partials.posts-renderer', compact('posts'))->render();
        }

        $posts = $query->simplePaginate(5);

        return view('pages.timeline', compact('posts'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Post::class);

        Log::info('Creating a new post:', ['request' => $request->all()]);

        $validated = $request->validate([
            'content' => 'required|string',
            'visibility' => 'required|in:public,private,group',
            'id_group' => 'nullable|integer|exists:group,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $imagePath = $request->file('image')->store('posts/images', 'public');
                Log::info('Image uploaded successfully:', ['path' => $imagePath]);
            } catch (\Exception $e) {
                Log::error('Failed to upload image:', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors(['image' => 'Failed to upload image. Please try again.']);
            }
        }

        Log::info('Data being passed to Post::create:', [
            'content' => $validated['content'],
            'visibility' => $validated['visibility'],
            'id_user' => Auth::id(),
            'id_group' => $validated['id_group'] ?? null,
            'image_url' => $imagePath,
        ]);

        $post = Post::create([
            'content' => $validated['content'],
            'visibility' => $validated['visibility'],
            'id_user' => Auth::id(),
            'id_group' => $validated['id_group'] ?? null,
            'image_url' => $imagePath,
        ]);

        Log::info('New post created:', ['post' => $post]);

        if ($request->id_group) {
            return redirect()->route('groups.show', ['id' => $request->id_group])
                ->with('success', 'Você criou um novo post no grupo com sucesso');
        } else {
            return redirect()->route('timeline.public')->with('success', 'Você criou um novo post com sucesso');
        }
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('update', $post);

        $validatedData = $request->validate([
            'content' => 'required|string|max:500',
            'visibility' => 'required|in:public,private,group',
        ]);

        $post->update($validatedData);

        return response()->json([
            'message' => 'Post updated successfully!',
            'content' => $post->content,
            'visibility' => $post->visibility,
        ], 200, ['Content-Type' => 'application/json']);
    }

    public function userPosts()
    {
        $user = Auth::user();
        $posts = $user->posts()->latest()->get(); 
        return view('pages.user-posts', compact('posts')); 
    }

    public function showCreateForm()
    {
        $this->authorize('create', Post::class);
        return view('pages.create-post');
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        return response()->json([
            'id' => $post->id,
            'content' => $post->content,
            'image_url' => $post->image_url,
            'user_name' => $post->user->name,
            'user_image' => $post->user->image_url ?? 'users/images/default.png',
            'created_at' => $post->created_at->format('d M, Y H:i'),
            'likes_count' => $post->likes->count(),
            'liked_by_current_user' => Auth::check() && $post->likes()->where('id_user', Auth::id())->exists(),
        ]);
    }

    public function showEditForm($id)
    {
        $post = Post::findOrFail($id); 
        return view('pages.edit-post', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('pages.edit-post', compact('post'));
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        Log::info('Attempting to delete post:', [
            'post_id' => $post->id,
            'post_user_id' => $post->id_user,
        ]);

        $this->authorize('delete', $post);
        $post->delete();

        return redirect()->route('profile.show', ['id' => $post->id_user])
            ->with('success', 'Post deleted successfully!');
    }

    public function like($postId)
    {
        if (Auth::check()) {
            $post = Post::findOrFail($postId);

            if (!$post->likes()->where('id_user', Auth::id())->exists()) {
                $post->likes()->create([
                    'id_user' => Auth::id(),
                    'id_post' => $post->id,
                    'visibility' => 'public',
                ]);
            }

            $previousUrl = url()->previous();
            return redirect($previousUrl)->with('success', 'Você curtiu o post!');
        }

        return redirect()->route('login')->with('error', 'Você precisa estar logado!');
    }

    public function unlike($postId)
    {
        if (Auth::check()) {
            $post = Post::findOrFail($postId);
            $like = $post->likes()->where('id_user', Auth::id())->first();

            if ($like) {
                $like->delete();
            }
        }

        return redirect()->back(); 
    }

    public function getComments($postId)
    {
        $post = Post::findOrFail($postId);
        $comments = $post->comments()->latest()->take(3)->get();

        return response()->json([
            'comments' => $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user_image_url' => $comment->user->image_url ?? 'users/images/default.png',
                    'user_name' => $comment->user->name,
                    'editable' => Auth::check() && Auth::id() === $comment->id_user
                ];
            }),
            'total_comments' => $post->comments()->count()
        ]);
    }

    public function loadMoreComments($postId, $offset)
    {
        $post = Post::findOrFail($postId);

        $comments = $post->comments()->latest()->skip($offset)->take(3)->get();

        return response()->json([
            'comments' => $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'user_image_url' => $comment->user->image_url ?? 'users/images/default.png',
                    'user_name' => $comment->user->name,
                    'content' => $comment->content,
                    'editable' => Auth::check() && Auth::id() === $comment->id_user
                ];
            }),
        ]);
    }

    public function storeComment(Request $request, $postId)
{
    $validated = $request->validate([
        'content' => 'required|string|max:1000',
    ]);

    $comment = new Comment();
    $comment->content = $validated['content'];
    $comment->id_user = Auth::id();
    $comment->id_post = $postId;
    $comment->save();

    return response()->json([
        'success' => true,
        'comment' => [
            'id' => $comment->id,
            'content' => $comment->content,
            'user_name' => $comment->user->name,
            'user_image' => asset('storage/' . ($comment->user->image_url ?? 'users/images/default.png')),
            'editable' => (Auth::id() === $comment->id_user)
        ],
    ]);
}


    public function toggleLike(Request $request, Post $post)
    {
        $user = Auth::user();

        if ($post->likes()->where('id_user', $user->id)->exists()) {
            $post->likes()->where('id_user', $user->id)->delete();
            $liked = false;
        } else {
            $post->likes()->create(['id_user' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likesCount' => $post->likes()->count()
        ]);
    }

}
