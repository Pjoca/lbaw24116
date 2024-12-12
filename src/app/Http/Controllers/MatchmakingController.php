<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class MatchmakingController extends Controller
{
    /**
     * Exibe o próximo perfil para o matchmaking.
     */
    public function index()
    {
        $currentUser = auth()->user();

        // Obtém o próximo perfil (excluindo o usuário autenticado)
        // Aqui você pode adicionar filtros (gênero, localização, interesses, etc.)
        $profile = User::where('id', '!=', $currentUser->id)
            ->inRandomOrder()
            ->first();

        // Retorna a view com o próximo perfil
        return view('pages.matchmaking', compact('profile'));
    }

    /**
     * Processa a interação do usuário (like/dislike).
     */
    public function interact(Request $request)
    {
        $currentUser = auth()->user();

        // Captura o ID do perfil e a ação (like/dislike)
        $profileId = $request->input('profile_id');
        $action = $request->input('action'); // "like" ou "dislike"

        // Aqui você pode salvar a interação no banco (como uma tabela de likes ou matches)
        // Exemplo:
        // Match::create([
        //     'user_id' => $currentUser->id,
        //     'matched_user_id' => $profileId,
        //     'action' => $action,
        // ]);

        // Redireciona para o próximo perfil
        return redirect()->route('matchmaking.index');
    }
}
