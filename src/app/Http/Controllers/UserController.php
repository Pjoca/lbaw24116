<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Post;
use App\Models\FriendshipRequest;
use App\Models\GroupMembershipRequest;
use App\Models\Message;
use App\Models\Like;
use App\Models\Comment;

class UserController extends Controller
{

    /**
     * Retrieve the profile information of a user.
     */
    public function show($id)
{
    $user = User::findOrFail($id);

    if ($user->visibility !== 'public' && !Auth::check()) {
        abort(403, 'You are not authorized to view this profile.');
    }

    $userData = [
        'id' => $user->id,
        'username' => $user->username,
        'name' => $user->name,
        'last_login' => $user->last_login,
        'image_url' => $user->image_url ?? 'users/images/default.png',
    ];

    $isOwnProfile = Auth::check() && Auth::id() === $user->id;
    if ($isOwnProfile) {
        $userData['email'] = $user->email;
    }

    $posts = Post::getAuthorizedPosts($user->id);

    $isFriend = false;
    $hasRequestedFriendship = false;
    $hasIncomingFriendRequest = false;
    $incomingFriendRequestId = null;

    if (Auth::check() && !$isOwnProfile) {
        $authUser = Auth::user();
        $isFriend = $authUser->isFriend($user);
        $hasRequestedFriendship = $authUser->hasRequestedFriendship($user);

        $incomingRequest = FriendshipRequest::where('id_sender', $user->id)
            ->where('id_receiver', $authUser->id)
            ->first();

        if ($incomingRequest) {
            $hasIncomingFriendRequest = true;
            $incomingFriendRequestId = $incomingRequest->id;
        }
    }

    // Fetch friend requests and group invites only for the profile owner
    $friendRequests = [];
    $groupInvites = [];
    if ($isOwnProfile) {
        $friendRequests = FriendshipRequest::where('id_receiver', Auth::id())
            ->with('sender') // Load sender details
            ->get();

        $groupInvites = GroupMembershipRequest::where('id_user', Auth::id())
            ->where('is_invite', true)
            ->where('request_status', 'pending')
            ->with('group') // Load group details
            ->get();

        // Log the group invites for debugging
        Log::info('Group invites fetched:', ['groupInvites' => $groupInvites->toArray()]);
    }

    return view('pages.profile', [
        'user' => $userData,
        'posts' => $posts,
        'isFriend' => $isFriend,
        'hasRequestedFriendship' => $hasRequestedFriendship,
        'hasIncomingFriendRequest' => $hasIncomingFriendRequest,
        'incomingFriendRequestId' => $incomingFriendRequestId,
        'friendRequests' => $friendRequests, // Pass friend requests
        'groupInvites' => $groupInvites, // Pass group invites
    ]);
}
    /**
     * Search users.
     */
    public function search(Request $request)
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

    public function delete($id)
    {
        DB::beginTransaction();


        try {
            // Find the user to be deleted
            $user = User::findOrFail($id);

            $this->authorize('update', $user);

            // Get the anonymous user (assume it's user with ID 1)
            $anonymousUser = User::where('username', 'anonymous')->first();
            if (!$anonymousUser) {
                return response()->json(['error' => 'Anonymous user not found.'], 404);
            }

            // Update all posts created by the user to be owned by the anonymous user
            Post::where('id_user', $user->id)->update(['id_user' => $anonymousUser->id]);

            // Update all messages sent or received by the user to be linked to the anonymous user
            Message::where('id_sender', $user->id)->update(['id_sender' => $anonymousUser->id]);
            Message::where('id_receiver', $user->id)->update(['id_receiver' => $anonymousUser->id]);

            // Update all comments made by the user to be owned by the anonymous user
            Comment::where('id_user', $user->id)->update(['id_user' => $anonymousUser->id]);

            // Update all likes made by the user to be owned by the anonymous user
            Like::where('id_user', $user->id)->update(['id_user' => $anonymousUser->id]);

            // Finally, delete the user
            $user->delete();

            DB::commit();

            return redirect()->route('home')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'An error occurred while deleting the user: ' . $e->getMessage()], 500);
        }
    }


}
