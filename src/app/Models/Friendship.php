<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;

    // Define the table name (optional if Laravel's naming convention is followed)
    protected $table = 'friendship';

    // Define the primary key (optional if it's 'id')
    protected $primaryKey = 'id';

    // Allow mass assignment for these fields
    protected $fillable = [
        'id_user1',
        'id_user2',
        'created_at',
    ];

    // Relationships

    /**
     * Get the first user in the friendship.
     */
    public function user1()
    {
        return $this->belongsTo(User::class, 'id_user1');
    }

    /**
     * Get the second user in the friendship.
     */
    public function user2()
    {
        return $this->belongsTo(User::class, 'id_user2');
    }

    // Add additional methods if needed for querying or business logic
}
