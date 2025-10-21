<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_gateway',
        'payment_method_type',
        'amount',
        'currency',
        'status',
        'gateway_transaction_id',
        'failure_message',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
