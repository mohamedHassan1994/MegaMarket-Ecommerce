<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Discount;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'shipping_address',
        'total_amount',
        'payment_method',
        'payment_status',
        'shipping_status',
        'status',
        'shipping_cost',
        'cod_fees',
        'tax_total',
        'promotion_type',
        'promotion_amount',
        'shipping_notes',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'total_amount'     => 'decimal:2',
        'shipping_cost'    => 'decimal:2',
        'cod_fees'         => 'decimal:2',
        'tax_total'        => 'decimal:2',
        'promotion_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'discount_order')
                    ->withPivot('applied_amount')
                    ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getItemsSubtotalAttribute()
    {
        return $this->orderItems->sum(function ($item) {
            return ($item->unit_price - ($item->discount ?? 0)) * $item->quantity;
        });
    }

    public function getDiscountTotalAttribute()
    {
        // Sum all applied discount amounts from pivot
        return $this->discounts->sum('pivot.applied_amount');
    }

    public function getGrandTotalAttribute()
    {
        $shipping   = $this->shipping_cost ?? 0;
        $cod        = $this->cod_fees ?? 0;
        $taxes      = $this->tax_total ?? 0;
        $promotion  = $this->promotion_amount ?? 0;
        $discounts  = $this->discount_total ?? 0;

        return $this->items_subtotal + $shipping + $cod + $taxes - $promotion - $discounts;
    }
}
