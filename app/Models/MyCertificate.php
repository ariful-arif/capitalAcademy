<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyCertificate extends Model
{
    protected $table = "my_certificate";
    protected $fillable = [
        'user_id',
        'organization_id',
        'team_id',
        'certificate_id',
        'created_at',
        'updated_at'
    ];

}
