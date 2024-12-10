<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMembershipRequest extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'group_membership_request';

    // Disable timestamps if not using created_at and updated_at
    public $timestamps = false;

    // Define the fillable properties for mass assignment
    protected $fillable = [
        'id_user',
        'id_group',
        'request_status',
        'requested_at',
        'is_invite',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'id_group');
    }
}
