<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Retrieve the public timeline of posts. Access: PUB
     */
    public function publicTimeline()
{
    $posts = Post::where('visibility', 'public')
        ->latest()
        ->paginate(10); // 10 posts por página

    return view('pages.timeline', compact('posts'));
}

public function personalizedTimeline()
{
    $user = Auth::user();

    $posts = Post::where('visibility', 'public')
        ->orWhere(function ($query) use ($user) {
            $query->where('visibility', 'private')
                  ->whereIn('id_user', $user->friends()->pluck('id'));
        })
        ->orWhere(function ($query) use ($user) {
            $query->where('visibility', 'group')
                  ->whereIn('id_group', $user->groups()->pluck('id')); // Se o usuário pertence ao grupo
        })
        ->latest()
        ->paginate(10);

    return view('pages.timeline', compact('posts'));
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
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Aceitar imagens com limite de 2MB
    ]);

    // Lidar com o upload da imagem
    $imagePath = null;
    if ($request->hasFile('image')) {
        // Salvar a imagem no diretório 'posts/images' e obter o caminho
        $imagePath = $request->file('image')->store('posts/images', 'public');
    }

    // Cria o post com o conteúdo e a imagem
    $post = Post::create([
        'content' => $validated['content'],
        'visibility' => $validated['visibility'],
        'id_user' => Auth::id(),
        'id_group' => $validated['id_group'] ?? null,
        'image_url' => $imagePath,  // Armazenar o caminho da imagem (se houver)
    ]);

    Log::info('New post created:', ['data' => $post]);

    // Redirecionar para a timeline
    return redirect()->route('timeline.public')
        ->with('success', 'Você criou um novo post com sucesso');
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
        'id_group' => 'nullable|integer|exists:groups,id',
        'image_url' => 'nullable|url',
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

    public function showCreateForm()
{
    $this->authorize('create', Post::class);

    return view('pages.create-post');
}
}

