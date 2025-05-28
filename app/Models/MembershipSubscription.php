<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MembershipSubscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'package_id',
        'payment_method',
        'paid_amount',
        'purchase_date',
        'expiry_date',
    ];

    /**
     * Get the package that owns the membership package.
     */
    public function package()
    {
        return $this->belongsTo(MembershipPackage::class, 'package_id');
    }

    /**
     * Get the user that owns the membership package.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
