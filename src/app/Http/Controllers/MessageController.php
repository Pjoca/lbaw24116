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

    // Fetch relevant contacts
    $messages = Message::where('id_sender', $userId)
        ->orWhere('id_receiver', $userId)
        ->get();

    $contactIds = $messages->pluck('id_sender')
        ->merge($messages->pluck('id_receiver'))
        ->unique()
        ->filter(function ($id) use ($userId) {
            return $id != $userId;
        });

    $contacts = User::whereIn('id', $contactIds)->get();

    
    $friends = User::where('id', '!=', $userId)
                   ->whereNotIn('id', $contactIds) 
                   ->get();

    return view('pages.messages', compact('contacts', 'friends'));
}

public function startConversation(Request $request)
{
    $userId = Auth::id(); // Authenticated user
    $friendId = $request->input('friend_id');

    
    if ($userId == $friendId || !User::find($friendId)) {
        return response()->json(['error' => 'Invalid friend ID'], 400);
    }

    
    // $existingConversation = Message::where(function ($query) use ($userId, $friendId) {
    //     $query->where('id_sender', $userId)
    //           ->where('id_receiver', $friendId);
    // })->orWhere(function ($query) use ($userId, $friendId) {
    //     $query->where('id_sender', $friendId)
    //           ->where('id_receiver', $userId);
    // })->exists();

    // if ($existingConversation) {
    //     return response()->json(['success' => true, 'message' => 'Conversation already exists.']);
    // }

    // Respond without creating a placeholder message
    return response()->json(['success' => true, 'message' => 'New conversation started.']);
}






public function getMessages($contactId)
{
    $userId = Auth::id();

    try {
        // Buscar mensagens entre o usuário autenticado e o contato fornecido
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

        // Formatar as mensagens para JSON
        $formattedMessages = $messages->map(function ($message) use ($userId) {
            return [
                'content' => $message->content,
                'sender_name' => $message->id_sender == $userId ? 'You' : User::find($message->id_sender)->name,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json($formattedMessages);
    } catch (\Exception $e) {
        \Log::error('Failed to load messages: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to load messages'], 500);
    }
}




public function sendMessage(Request $request)
{
    $request->validate([
        'content' => 'required|string|max:1000',
        'receiver_id' => 'required|exists:user,id',
    ]);

    try {
        $message = new Message([
            'content' => $request->input('content'),
            'id_sender' => Auth::id(),
            'id_receiver' => $request->input('receiver_id'),
            'created_at' => now(), // Definir manualmente para evitar o uso de updated_at
        ]);

        $message->save();

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully!',
        ]);
    } catch (\Exception $e) {
        \Log::error('Failed to send message: ' . $e->getMessage()); // Log para debugging
        return response()->json([
            'success' => false,
            'error' => 'Failed to send message.',
        ], 500);
    }
}



}

