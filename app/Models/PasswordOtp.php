<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PasswordOtp extends Model
{
    protected $fillable = [
        'email',
        'otp',
        'expires_at',
    ];

    protected $dates = ['expires_at'];
}

