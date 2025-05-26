<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bundle_category extends Model
{
    public function bundles() {
        return $this->hasMany(CourseBundle::class, 'bundle_category_id');
    }
}
