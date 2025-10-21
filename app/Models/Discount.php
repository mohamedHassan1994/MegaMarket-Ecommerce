<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',          // percent | fixed
        'value',         // discount value
        'is_exclusive',  // whether it excludes other discounts
        'starts_at',
        'ends_at',
        'usage_limit',
        'used_count',
    ];

    /**
     * A discount can apply to many orders.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'discount_order')
                    ->withPivot('applied_amount')
                    ->withTimestamps();
    }
}
