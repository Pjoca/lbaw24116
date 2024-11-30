<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMembership extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'group_membership';

    // Disable timestamps if not using created_at and updated_at
    public $timestamps = false;

    // Define the fillable properties for mass assignment
    protected $fillable = [
        'id_user',
        'id_group',
        'joined_at',
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
