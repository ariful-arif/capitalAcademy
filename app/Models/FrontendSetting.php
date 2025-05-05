<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrontendSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'footer_video',
        'banner_video',
        'home_page_body_video',
    ];

}
