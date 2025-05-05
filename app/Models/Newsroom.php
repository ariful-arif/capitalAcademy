<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Newsroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_slug', 'title', 'slug', 'description', 'thumbnail', 'banner', 'keywords', 'is_popular', 'status',
    ];


    public function user() {
        return $this->belongsTo(User::class);
    }
}
