<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


use App\Models\Post;



class UserController extends Controller
{

    /**
     * Retrieve the profile information of a user.
     */
    public function show($id)
{
    // Find the user by ID or return a 404 response
    $user = User::findOrFail($id);

    // Check if the profile is public or if the user is authenticated and authorized to view it
    if ($user->visibility !== 'public' && !Auth::check()) {
        abort(403, 'You are not authorized to view this profile.');
    }

    // Prepare the user data for the response
    $userData = [
        'id' => $user->id,
        'username' => $user->username,
        'name' => $user->name,
        'last_login' => $user->last_login,
        'image_url' => $user->image_url ?? 'users/images/default.png', // Ensure image_url is always set
    ];

    // Include email if it's the authenticated user's own profile
    $isOwnProfile = Auth::check() && Auth::id() === $user->id;
    if ($isOwnProfile) {
        $userData['email'] = $user->email;
    }

    // Retrieve posts based on profile ownership or public visibility
    $posts = Post::getAuthorizedPosts($user->id);

    // Return the user profile and posts
    return view('pages.profile', [
        'user' => $userData,
        'posts' => $posts,
    ]);
}



    /**
     * Search users.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $limit = $request->input('limit', null); 
        
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
                strtolower($query) . '%', // Starts with query
                '%' . strtolower($query) . '%', // Contains query
            ])
            ->where(function ($q) use ($query) {
                $q->whereRaw('LOWER(name) = ?', [strtolower($query)])
                  ->orWhereRaw('LOWER(name) LIKE ?', [strtolower($query) . '%'])
                  ->orWhereRaw('LOWER(name) LIKE ?', ['%' . strtolower($query) . '%']);
            })
            ->orderBy('priority')
            ->orderBy('name');
    
        // Apply the limit only if it's provided
        if ($limit) {
            $users = $users->take($limit);
        }
    
        $users = $users->get();
    
        if ($request->ajax()) {
            return view('partials.search-results', compact('users'))->render();
        }
    
        return view('search.results', compact('users', 'query'));
    }

    /**
     * Update the profile information of the authenticated user.
     */
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Validar entrada do usuário
    $request->validate([
        'name' => 'required|string|max:250',
        'email' => [
            'required',
            'email',
            'max:250',
            Rule::unique('user', 'email')->ignore($user->id), // Email único (exceto para o usuário atual)
            function ($attribute, $value, $fail) {
                if (!str_ends_with($value, '@up.pt')) {
                    $fail('The email must be a valid @up.pt email address.');
                }
            },
        ],
        'username' => [
            'required',
            'string',
            'max:50',
            Rule::unique('user', 'username')->ignore($user->id), // Username único (exceto para o usuário atual)
        ],
        'password' => 'nullable|min:8|confirmed', // Senha opcional, mas deve coincidir
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Apenas formatos de imagem suportados
        'visibility' => 'required|in:public,private',
    ]);

    // Atualizar os dados do usuário
    $user->name = $request->name;
    $user->email = $request->email;
    $user->username = $request->username;
    $user->visibility = $request->visibility;

    // Atualizar senha se fornecida
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    // Atualizar imagem de perfil, se fornecida
    if ($request->hasFile('image')) {
        // Apagar imagem antiga, se houver
        if ($user->image_url) {
            Storage::delete('public/' . $user->image_url);
        }

        // Armazenar nova imagem
        $path = $request->file('image')->store('profile_images', 'public');
        $user->image_url = $path;
    }

    // Salvar alterações
    $user->save();

    // Redirecionar com mensagem de sucesso
    return redirect()->route('profile.show', $id)
        ->with('success', 'Profile updated successfully!');
}





    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Verifica se o usuário autenticado é o dono do perfil
        if (Auth::id() !== $user->id) {
            abort(403, 'You are not authorized to edit this profile.');
        }

        return view('pages.edit', compact('user'));
    }


}
