<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'like'; // Especifica o nome da tabela, que é "like"
    
    // Desabilita o gerenciamento de timestamps
    public $timestamps = false; 

    // Defina os atributos que podem ser preenchidos (opcional, dependendo da sua necessidade)
    protected $fillable = ['id_user', 'id_post', 'id_comment', 'visibility', 'created_at'];

    // Relacionamentos (se necessário)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'id_post');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'id_comment');
    }
}

