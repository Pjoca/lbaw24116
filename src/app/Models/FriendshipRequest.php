<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendshipRequest extends Model
{
    use HasFactory;

    // Define the table name (optional if Laravel's naming convention is followed)
    protected $table = 'friendship_request';

    // Define the primary key (optional if it's 'id')
    protected $primaryKey = 'id';

    // Allow mass assignment for these fields
    protected $fillable = [
        'id_sender',
        'id_receiver',
        'status',
        'created_at',
    ];

    // Relationships

    /**
     * Get the sender of the friendship request.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'id_sender');
    }

    /**
     * Get the receiver of the friendship request.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'id_receiver');
    }

}
