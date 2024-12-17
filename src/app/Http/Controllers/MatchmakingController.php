<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GroupMembership;

class MatchmakingController extends Controller
{
    public function index()
{
    $user = auth()->user();
    $userGroups = $user->groups()->pluck('id')->toArray();

    // Paginar os usuários
    $sameGroupUsers = User::whereHas('groups', function ($query) use ($userGroups) {
        $query->whereIn('id', $userGroups);
    })->where('id', '!=', $user->id)->paginate(9);

    $otherUsers = User::whereDoesntHave('groups', function ($query) use ($userGroups) {
        $query->whereIn('id', $userGroups);
    })->where('id', '!=', $user->id)->paginate(9);

    return view('pages.matchmaking', [
        'sameGroupUsers' => $sameGroupUsers,
        'otherUsers' => $otherUsers,
        'currentUser' => $user, // Passa o usuário autenticado para verificação de amizade
    ]);
}

public function loadMoreUsers(Request $request)
{
    try {
        $user = auth()->user();
        $userGroups = $user->groups()->pluck('id')->toArray();

        // Verifica se o tipo é 'sameGroup' ou 'otherUsers'
        if ($request->type === 'sameGroup') {
            $users = User::whereHas('groups', function ($query) use ($userGroups) {
                $query->whereIn('id', $userGroups);
            })->where('id', '!=', $user->id)->paginate(9, ['*'], 'page', $request->page);
        } else if ($request->type === 'otherUsers') {
            $query = User::whereDoesntHave('groups', function ($query) use ($userGroups) {
                $query->whereIn('id', $userGroups);
            })->where('id', '!=', $user->id);

            // Se o filtro "não amigos" estiver ativo
            if ($request->filter === 'not-friends') {
                $query->whereDoesntHave('friends', function($query) use ($user) {
                    $query->where('friend_id', $user->id);
                });
            }

            $users = $query->paginate(9, ['*'], 'page', $request->page);
        } else {
            return response()->json(['error' => 'Tipo inválido'], 400);
        }

        // Adiciona o status de amizade a cada usuário
        $users->getCollection()->transform(function ($userItem) use ($user) {
            $userItem->is_friend = $user->isFriend($userItem); // Adiciona o status de amizade
            return $userItem;
        });

        // Retorna os dados em JSON
        return response()->json(['users' => $users]);
    } catch (\Exception $e) {
        \Log::error('Erro ao carregar mais usuários: ' . $e->getMessage());
        return response()->json(['error' => 'Erro ao carregar mais usuários'], 500);
    }
}




}

