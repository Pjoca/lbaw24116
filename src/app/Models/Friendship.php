<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        if ($userId1 === $userId2) {
            throw new \InvalidArgumentException('Users cannot be friends with themselves.');
        }

        DB::transaction(function () use ($userId1, $userId2) {
            // Insert both directions of the friendship
            self::updateOrCreate(['id_user1' => $userId1, 'id_user2' => $userId2]);
            self::updateOrCreate(['id_user1' => $userId2, 'id_user2' => $userId1]);
        });
    }

    public static function removeFriendship($userId1, $userId2)
    {
        DB::transaction(function () use ($userId1, $userId2) {
            // Remove both directions of the friendship
            self::where(['id_user1' => $userId1, 'id_user2' => $userId2])->delete();
            self::where(['id_user1' => $userId2, 'id_user2' => $userId1])->delete();
        });
    }

    // Add additional methods if needed for querying or business logic
}
