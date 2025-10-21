<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DiscountOrder extends Pivot
{
    protected $table = 'discount_order';

    protected $fillable = [
        'discount_id',
        'order_id',
        'applied_amount',
    ];

    /**
     * Pivot belongs to a discount.
     */
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    /**
     * Pivot belongs to an order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
