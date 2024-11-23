<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps = false;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'name',
        'password',
        'visibility',
        'account_status',
        'image_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_login' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationships
     */

     public function posts(): HasMany
     {
         return $this->hasMany(Post::class, 'id_user');
     }
    
     public function admin()
     {
        return $this->hasOne(Admin::class, 'id');
     }

     public function isAdmin()
     {
        return $this->admin !== null;
     }   

     public function friends()
     {
         return $this->belongsToMany(User::class, 'friendship', 'id_user1', 'id_user2')
                     ->wherePivot('id_user1', Auth::id())
                     ->orWherePivot('id_user2', Auth::id())
                     ->distinct('user.id'); // Explicitly make the user ID distinct
     }
}
