<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
        'organization_id',
        'team_members',
        'member_ids',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'member_ids' => 'array',
    ];


    public function getMemberIdsAttribute($value)
    {
        return !empty($value) ? json_decode($value, true) : [];
    }

}
