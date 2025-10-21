<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'old_stock',
        'new_stock',
        'change',
        'note',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
