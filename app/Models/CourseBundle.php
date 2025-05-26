<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseBundle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'user_id',
        'course_ids',
        'subscription_limit',
        'thumbnail',
        'price',
        'bundle_details',
        'status',
        'banner',
        'trailer_video_link',
        'bundle_category_id'
    ];
    public function bundlePayments()
    {
        return $this->hasMany(BundlePayment::class, 'bundle_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Bundle_category::class, 'bundle_category_id')->withDefault();
    }

}
