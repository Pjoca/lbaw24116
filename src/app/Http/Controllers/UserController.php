<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    // Encontre o usuário pelo ID
    $user = User::findOrFail($id);

    // Assegure que o usuário autenticado está atualizando o próprio perfil
    $this->authorize('update', $user);

    // Validação dos dados de entrada, incluindo a unicidade do username
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'visibility' => 'required|in:public,private',
        'password' => 'nullable|string|min:8', // Torne a senha opcional
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validação para imagem
        'username' => 'required|string|max:50|unique:user,username,' . $user->id, // Validação de unicidade do username, exceto para o próprio usuário
    ]);

    // Se uma nova imagem for carregada, armazene-a
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('images', 'public');
        $validated['image_url'] = $path;
    }

    // Se uma nova senha for fornecida, criptografe-a antes de atualizar
    if ($request->filled('password')) {
        $validated['password'] = bcrypt($request->password); // Atualiza a senha
    } else {
        // Se a senha não foi fornecida, mantemos a senha atual
        unset($validated['password']); // Não altera a senha
    }

    // Atualize o perfil do usuário
    $user->update($validated);

    // Redirecione de volta para a página de perfil com uma mensagem de sucesso
    return redirect()->route('profile.show', ['id' => $user->id])
                     ->with('success', 'Profile updated successfully');
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
