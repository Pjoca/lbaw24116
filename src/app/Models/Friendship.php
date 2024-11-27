<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;

    // Define the table name (optional if Laravel's naming convention is followed)
    protected $table = 'friendship';

    public $timestamps = false;

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

    public static function createFriendship($userId1, $userId2)
    {
        // Ensure user IDs are always stored in ascending order
        $friendship = new self();
        $friendship->id_user1 = min($userId1, $userId2);
        $friendship->id_user2 = max($userId1, $userId2);
        $friendship->created_at = now();
        $friendship->save();

        return $friendship;
    }
    // Add additional methods if needed for querying or business logic
}
