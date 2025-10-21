<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
        'total_votes',
    ];


    protected $casts = [
        'rating' => 'integer',
        'total_votes' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function scopeHighestRated($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    public function scopeLatest($query)
    {
        return $this->orderBy('created_at', 'desc');
    }

    public function hasComment()
    {
        return !empty($this->comment);
    }

    public static function averageRatingForProduct(int $productId): float
    {
        return (float) self::where('product_id', $productId)->avg('rating');
    }

    public static function totalReviewsForProduct(int $productId): int
    {
        return self::where('product_id', $productId)->count();
    }
}
