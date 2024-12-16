<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
{
    $userId = Auth::id();

    $contacts = Message::where('id_sender', $userId)
        ->orWhere('id_receiver', $userId)
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'desc') 
        ->get()
        ->unique(function ($message) use ($userId) {
            
            return $message->id_sender === $userId ? $message->id_receiver : $message->id_sender;
        })
        ->map(function ($message) use ($userId) {
            
            $contact = $message->id_sender === $userId ? $message->receiver : $message->sender;
            $contact->last_message_at = $message->created_at;
            return $contact;
        });
    
    $existingContactIds = $contacts->pluck('id');
    $friendIds = DB::table('friendship')
        ->where('id_user1', $userId)
        ->pluck('id_user2')
        ->merge(
            DB::table('friendship')
                ->where('id_user2', $userId)
                ->pluck('id_user1')
        )
        ->unique(); // Garantir sem duplicados

        $friends = User::where('id', '!=', $userId)
            ->whereNotIn('id', $existingContactIds)
            ->whereIn('id', $friendIds)
           ->get();

           return view('pages.messages', compact('contacts', 'friends'));
}


public function startConversation(Request $request)
{
    $userId = Auth::id();
    $friendId = $request->input('friend_id');

    if ($userId == $friendId || !User::find($friendId)) {
        return response()->json(['error' => 'Invalid friend ID'], 400);
    }

    $contact = User::find($friendId);

    return response()->json([
        'success' => true,
        'contact' => [
            'id' => $contact->id,
            'name' => $contact->name,
            'image_url' => asset('storage/' . ($contact->image_url ?? 'users/images/default.png')),
        ],
    ]);
}


public function getMessages(Request $request, $contactId)
{
    $userId = Auth::id();
    $perPage = 10;
    $page = $request->input('page', 1);

    // Buscar mensagens entre o usuário autenticado e o contato
    $query = Message::where(function ($query) use ($userId, $contactId) {
            $query->where('id_sender', $userId)
                  ->where('id_receiver', $contactId);
        })
        ->orWhere(function ($query) use ($userId, $contactId) {
            $query->where('id_sender', $contactId)
                  ->where('id_receiver', $userId);
        })
        ->orderBy('created_at', 'asc'); // Mensagens mais recentes primeiro

    // Paginação das mensagens
    $messages = $query->paginate($perPage, ['*'], 'page', $page);

    // As mensagens estão em ordem decrescente, vamos revertê-las para mandar
    // em ordem cronológica (da mais antiga para a mais recente)
    $formattedMessages = collect($messages->items())->map(function ($message) use ($userId) {
        return [
            'content' => $message->content,
            'sender_name' => $message->id_sender == $userId ? 'You' : User::find($message->id_sender)->name,
            'created_at' => $message->created_at->format('Y-m-d H:i:s'),
        ];
    });

    return response()->json([
        'data' => $formattedMessages,
        'current_page' => $messages->currentPage(),
        'last_page' => $messages->lastPage(),
    ]);
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

