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

    // Obter mensagens relevantes (como sender ou receiver)
    $messages = Message::where('id_sender', $userId)
        ->orWhere('id_receiver', $userId)
        ->get();

    // Obter os IDs únicos de contatos (excluindo o próprio usuário)
    $contactIds = $messages->pluck('id_sender')
        ->merge($messages->pluck('id_receiver'))
        ->unique()
        ->filter(function ($id) use ($userId) {
            return $id != $userId;
        });

    // Buscar os contatos pelo ID
    $contacts = User::whereIn('id', $contactIds)->get();

    return view('pages.messages', compact('contacts'));
}





public function getMessages($contactId)
{
    $userId = Auth::id(); // Usuário autenticado

    // Buscar mensagens entre o usuário autenticado e o contato fornecido
    $messages = Message::where(function ($query) use ($userId, $contactId) {
            $query->where('id_sender', $userId)
                  ->where('id_receiver', $contactId);
        })
        ->orWhere(function ($query) use ($userId, $contactId) {
            $query->where('id_sender', $contactId)
                  ->where('id_receiver', $userId);
        })
        ->orderBy('created_at', 'asc') // Ordenar por data
        ->get();

    // Transformar mensagens para o formato JSON
    $formattedMessages = $messages->map(function ($message) use ($userId) {
        return [
            'content' => $message->content,
            'sender_name' => $message->id_sender == $userId ? 'You' : User::find($message->id_sender)->name,
            'created_at' => $message->created_at->format('Y-m-d H:i:s'),
        ];
    });

    return response()->json($formattedMessages);
}



public function sendMessage(Request $request)
{
    $request->validate([
        'content' => 'required|string|max:1000',
        'receiver_id' => 'required|exists:user,id',
    ]);

    $message = new Message();
    $message->content = $request->input('content');
    $message->id_sender = Auth::id();
    $message->id_receiver = $request->input('receiver_id');
    $message->created_at = now(); // Adiciona o valor manualmente
    $message->save();

    return response()->json([
        'success' => true,
        'message' => 'Message sent successfully!',
    ]);
}




}

