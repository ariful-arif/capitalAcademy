<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicPage extends Model
{
    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'dynamic_pages';

    // Define the fillable attributes
    protected $fillable = ['key', 'value'];
}