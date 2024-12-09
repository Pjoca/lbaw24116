<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comment'; 

    public $timestamps = false;

    protected $fillable = [
        'content',
        'id_user',
        'id_post',
        'edited_at',
        'deleted_at',
        'visibility',
    ];

    
    public function post()
    {
        return $this->belongsTo(Post::class, 'id_post');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

public function scopeLatest($query)
{
    return $query->orderBy('edited_at', 'desc');
}

}