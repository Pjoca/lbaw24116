<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'id_sender', 'id_receiver', 'visibility'];
    protected $table = 'message';

    // public $timestamps = false; //tive que fazer isto porque não temos na tabela updated_at 

    public function sender()
    {
        return $this->belongsTo(User::class, 'id_sender');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'id_receiver');
    }

    public function getConversation(Request $request, $contactId)
    {
    $userId = auth()->id(); // Usuário autenticado

    // Buscar mensagens entre o usuário autenticado e o contato
    $messages = Message::where(function ($query) use ($userId, $contactId) {
        $query->where('id_sender', $userId)
              ->where('id_receiver', $contactId);
    })->orWhere(function ($query) use ($userId, $contactId) {
        $query->where('id_sender', $contactId)
              ->where('id_receiver', $userId);
    })->orderBy('created_at', 'asc') // Ordenar por data (opcional)
      ->get();

    return response()->json($messages); // Retornar como JSON
    }

}
