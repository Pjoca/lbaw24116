<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GroupMembership;

class MatchmakingController extends Controller
{
    public function index()
    {
        // Obter o usuário autenticado
        $user = auth()->user();

        // Obter os grupos do usuário
        $userGroups = $user->groups()->pluck('id')->toArray();

        // Obter usuários em grupos em comum
        $sameGroupUsers = User::whereHas('groups', function ($query) use ($userGroups) {
            // Verifica se o usuário está em algum dos grupos do usuário autenticado
            $query->whereIn('id', $userGroups);
        })
        ->where('id', '!=', $user->id)  // Exclui o próprio usuário
        ->get();

        // Obter usuários que não estão em grupos em comum
        $otherUsers = User::whereDoesntHave('groups', function ($query) use ($userGroups) {
            // Verifica se o usuário não está em nenhum dos grupos do usuário autenticado
            $query->whereIn('id', $userGroups);
        })
        ->where('id', '!=', $user->id)  // Exclui o próprio usuário
        ->get();

        // Passar os dados para a view
        return view('pages.matchmaking', compact('sameGroupUsers', 'otherUsers'));
    }
}

