<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
    $userId = Auth::id();

    // Obter os IDs únicos de contatos relacionados às mensagens
    $contactIds = Message::where('id_sender', $userId)
        ->orWhere('id_receiver', $userId)
        ->pluck('id_sender', 'id_receiver')
        ->flatten()
        ->unique()
        ->filter(fn($id) => $id !== $userId);

    // Buscar os contatos pelo ID
    $contacts = User::whereIn('id', $contactIds)->get();

    return view('pages.messages', compact('contacts'));
    }




    public function getMessages($contactId)
    {
    $userId = Auth::id();

    // Buscar mensagens entre o usuário autenticado e o contato
    $messages = Message::where(function ($query) use ($userId, $contactId) {
            $query->where('id_sender', $userId)
                  ->where('id_receiver', $contactId);
        })
        ->orWhere(function ($query) use ($userId, $contactId) {
            $query->where('id_sender', $contactId)
                  ->where('id_receiver', $userId);
        })
        ->orderBy('created_at', 'asc')
        ->get();

    // Retornar os dados em JSON para o JavaScript
    return response()->json($messages->map(function ($message) {
        return [
            'content' => $message->content,
            'sender_name' => User::find($message->id_sender)->name,
            'created_at' => $message->created_at->format('Y-m-d H:i:s'),
        ];
    }));
    }
}

