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
    /**
     * Retrieve the public timeline of posts. Access: PUB
     */

    
     public function publicTimeline(Request $request)
     {
         // Retrieve public posts
         $query = Post::where('visibility', 'public')->latest();
     
         // Handle AJAX requests for dynamic loading
         if ($request->ajax()) {
             $posts = $query->simplePaginate(5); // Fetch 5 posts per page for infinite scroll
             return view('partials.posts-renderer', compact('posts'))->render(); // Render the post partials
         }
     
         // For the initial page load, retrieve the first 5 posts
         $posts = $query->simplePaginate(5);
     
         return view('pages.timeline', compact('posts'));
     }


    public function personalizedTimeline(Request $request)
    {
        $user = Auth::user();

        // Get the IDs of the user's friends
        $friendIds = $user->friendships()->pluck('id_user2')->toArray();

        // Retrieve public posts, private posts of friends, and the user's own posts
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

        // For AJAX requests, return only the posts
        if ($request->ajax()) {
            $posts = $query->simplePaginate(5); // Load 5 posts per page
            return view('partials.posts-renderer', compact('posts'))->render(); // Render posts partial
        }

        // For the initial page load, retrieve the first 5 posts
        $posts = $query->simplePaginate(5);

        return view('pages.timeline', compact('posts'));
    }

    
    /**
     * Create a new post by an authenticated user. Access: USR
     */
    public function create(Request $request)
    {
        $this->authorize('create', Post::class);

        // Validate the incoming request
        $validated = $request->validate([
            'content' => 'required|string',
            'visibility' => 'required|in:public,private,group',
            'id_group' => 'nullable|integer|exists:group,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image
        ]);

        // Handle image upload
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

        // Create the post in the database
        $post = Post::create([
            'content' => $validated['content'],
            'visibility' => $validated['visibility'],
            'id_user' => Auth::id(),
            'id_group' => $validated['id_group'] ?? null,
            'image_url' => $imagePath, // Store the image path
        ]);

        Log::info('New post created:', ['post' => $post]);

        return redirect()->route('timeline.public')->with('success', 'Você criou um novo post com sucesso');
    }



    /**
     * Edit an existing post by the authenticated user. Access: OWN
     */
public function update(Request $request, $id)
{
    $post = Post::findOrFail($id);

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

    // Authorization check if necessary
    if (Auth::id() !== $post->id_user) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    return response()->json([
        'id' => $post->id,
        'content' => $post->content,
        'image_url' => $post->image_url,
        'user_name' => $post->user->name,
        'user_image' => $post->user->image_url ?? 'users/images/default.png',
        'created_at' => $post->created_at->format('d M, Y H:i'),
        'likes_count' => $post->likes->count(),
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
    // Find the post by ID
    $post = Post::findOrFail($id);

    // Log the retrieved post for debugging purposes
    Log::info('Attempting to delete post:', [
        'post_id' => $post->id,
        'post_user_id' => $post->id_user,
    ]);

    // Authorize the action
    $this->authorize('delete', $post);

    // Delete the post
    $post->delete();

    // Redirect to the user's profile with a success message
    return redirect()->route('profile.show', ['id' => $post->id_user])
        ->with('success', 'Post deleted successfully!');
}

public function like($postId)
{
    if (Auth::check()) {
        $post = Post::findOrFail($postId);

        // Verifica se o usuário já curtiu o post
        if (!$post->likes()->where('id_user', Auth::id())->exists()) {
            // Lógica para curtir o post
            $post->likes()->create([
                'id_user' => Auth::id(),
                'id_post' => $post->id,
                'visibility' => 'public', // Ou como for necessário
            ]);
        }

        // Recupera a URL da página anterior (pode ser a timeline ou outra)
        $previousUrl = url()->previous();

        // Redireciona de volta para a página anterior (onde o usuário estava)
        return redirect($previousUrl)->with('success', 'Você curtiu o post!');
    }

    return redirect()->route('login')->with('error', 'Você precisa estar logado!');
}

    
    public function unlike($postId)
{
    
    if (Auth::check()) {
        $post = Post::findOrFail($postId);

       
        $like = $post->likes()->where('id_user', Auth::id())->first();  // Alterar 'user_id' para 'id_user'

        if ($like) {
            
            $like->delete();
        }
    }

    return redirect()->back(); 
}

public function getComments($postId)
{
    $post = Post::findOrFail($postId);

    // Pega os 3 primeiros comentários
    $comments = $post->comments()->latest()->take(3)->get();

    return response()->json([
        'comments' => $comments,
        'total_comments' => $post->comments()->count()
    ]);
}

public function loadMoreComments($postId, $offset)
{
    $post = Post::findOrFail($postId);

    // Carrega mais 3 comentários a partir do offset
    $comments = $post->comments()->latest()->skip($offset)->take(3)->get();

    // Retorna os comentários em formato JSON
    return response()->json([
        'comments' => $comments->map(function ($comment) {
            return [
                'user_image_url' => $comment->user->image_url ?? 'users/images/default.png',
                'user_name' => $comment->user->name,
                'content' => $comment->content,
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

    return redirect()->back();
}

public function toggleLike(Request $request, Post $post)
{
    $user = Auth::user();

    // Verifica se o usuário já deu like
    if ($post->likes()->where('id_user', $user->id)->exists()) {
        // Remove o like existente
        $post->likes()->where('id_user', $user->id)->delete();
        $liked = false;
    } else {
        // Adiciona um novo like
        $post->likes()->create(['id_user' => $user->id]);
        $liked = true;
    }

    // Retorna a contagem atualizada de likes em JSON
    return response()->json([
        'liked' => $liked,
        'likesCount' => $post->likes()->count()
    ]);
}

}   


