<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'id_sender', 'id_receiver', 'created_at', 'visibility'];
    protected $table = 'message';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public $timestamps = false; // Ignorar updated_at

    public function sender()
    {
        return $this->belongsTo(User::class, 'id_sender');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'id_receiver');
    }
}
