<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post'; // Adjust if your table is named differently

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false; // Default for created_at and updated_at

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'visibility',
        'id_user',
        'id_group',
        'image_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'edited_at' => 'datetime',
    ];

    /**
     * Get the user that owns the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public static function getAuthorizedPosts($profileOwnerId)
    {
        $authUserId = Auth::id();

        if ($authUserId === $profileOwnerId) {
            // Show all posts by the authenticated user
            return self::where('id_user', $authUserId)
                ->latest()
                ->get();
        }

        if (Auth::check()){
            $areFriends = Auth::user()->isFriend(User::find($profileOwnerId));
        }else{
            $areFriends = false;
        }

        if ($areFriends) {
            return self::where('id_user', $profileOwnerId)
                ->whereIn('visibility', ['public', 'private'])
                ->latest()
                ->get();
        }
        return self::where('id_user', $profileOwnerId)
            ->where('visibility', 'public')
            ->latest()
            ->get();
    }

}
