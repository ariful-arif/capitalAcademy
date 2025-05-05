<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPackageEnrollment extends Model
{
    //
    protected $table = "subscription_package_enrollments";
    protected $fillable = [
        'user_id',
        'subscription_type',
        'subscription_package_id',
        'payment_method',
        'license_amount',
        'license_user',
        'entry_date',
        'expiry_date',
        'created_at',
        'updated_at'
    ];
}
