<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPackageHistory extends Model
{
    protected $table = "subscription_package_histories";
    protected $fillable = [
        'user_id',
        'subscription_type',
        'subscription_package_id',
        'payment_method',
        'amount',
        'invoice',
        'entry_date',
        'expiry_date',
        'admin_revenue',
        'instructor_revenue',
        'tax',
        'instructor_payment_status',
        'transaction_id',
        'session_id',
        'coupon',
        'created_at',
        'updated_at'
    ];
}
