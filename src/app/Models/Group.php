<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'group';

    // Disable timestamps if not using created_at and updated_at
    public $timestamps = false;

    // Define the fillable properties for mass assignment
    protected $fillable = [
        'name',
        'description',
        'visibility',
        'created_at',
        'id_owner',
        'image_url',
    ];

    // Relationships

    /**
     * The owner of the group.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'id_owner');
    }

    /**
     * Users who are members of the group.
     */
    public function memberships()
    {
        return $this->hasMany(GroupMembership::class, 'id_group');
    }

    /**
     * Users who have requested membership in the group.
     */
    public function membershipRequests()
    {
        return $this->hasMany(GroupMembershipRequest::class, 'id_group');
    }

    /**
     * Posts associated with the group.
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'id_group');
    }
}