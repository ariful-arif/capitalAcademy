<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use function Laravel\Prompts\table;

// class SubscriptionPackage extends Model
// {
//     //
//     protected $table = 'subscription_package';
// }

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPackage extends Model
{
    protected $table = 'subscription_package';

    // Allow mass assignment for these fields
    protected $fillable = [
        'package_name',
        'short_description',
        'subscription_type',
        'package_type',
        'package_duration',
        'status',
        'is_paid',
        'price',
        'discount_flag',
        'discounted_price',
        'info',
        'banner',
        'created_at',
        'updated_at',
    ];
}
