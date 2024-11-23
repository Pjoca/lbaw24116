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
        ->paginate(30); // nesta opçao podem dizer quantos posts querem por pagina -> igual ao exemplo de baixo dos myposts

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

        // Validate the incoming request
        $validated = $request->validate([
            'content' => 'required|string',
            'visibility' => 'required|in:public,private,group',
            'id_group' => 'nullable|integer|exists:groups,id',
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
    $post = Post::findOrFail($id); // Encontra o post pelo ID

    $validated = $request->validate([
        'content' => 'required|string',
        'visibility' => 'required|in:public,private,group',
    ]);

    // Atualiza o post com os dados validados
    $post->update($validated);

    return redirect()->route('timeline.public')->with('success', 'Post atualizado com sucesso!');
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

public function show(Post $post)
{
    return view('pages.edit-post', compact('post'));
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

public function delete(Post $post)
{
    
    $this->authorize('delete', $post);


    $post->delete();

   
    return redirect()->route('posts.userPosts')
                     ->with('success', 'Post excluído com sucesso!');
}




}


