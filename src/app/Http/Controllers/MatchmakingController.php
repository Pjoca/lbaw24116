<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class MatchmakingController extends Controller
{
    /**
     * Exibe o próximo perfil para o matchmaking.
     */
    public function index(Request $request)
{
    $currentUser = auth()->user();
    $seenProfiles = session('seen_profiles', []);

    // Seleciona perfis que o usuário ainda não viu
    $profile = User::where('id', '!=', $currentUser->id)
        ->whereNotIn('id', $seenProfiles)
        ->inRandomOrder()
        ->first();

    // Salva o ID do perfil visto na sessão
    if ($profile) {
        $seenProfiles[] = $profile->id;
        session(['seen_profiles' => $seenProfiles]);
    } else {
        session()->forget('seen_profiles'); // Reseta se não houver mais perfis
    }

    // Retorna apenas o conteúdo parcial se for AJAX
    if ($request->ajax()) {
        return view('partials.profile-cards-matchmaking', compact('profile'))->render();
    }

    // Retorna a página principal se for uma requisição padrão
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
        $action = $request->input('action');

        // Exemplo de salvar interação no banco de dados
        // Match::updateOrCreate([
        //     'user_id' => $currentUser->id,
        //     'matched_user_id' => $profileId,
        // ], [
        //     'action' => $action
        // ]);

        // Redireciona para o próximo perfil
        return redirect()->route('matchmaking.index');
    }
}
