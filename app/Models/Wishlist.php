<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    protected $table = 'wishlists';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public static function isProductInWishlist($userId, $productId)
    {
        return self::where('user_id', $userId)->where('product_id', $productId)->exists();
    }

    /**
     * Add a product to user's wishlist.
     */
    public static function addToWishlist($userId, $productId): self
    {
        return self::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
    }

    /**
     * Remove a product from user's wishlist.
     */
    public static function removeFromWishlist($userId, $productId): bool
    {
        return self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete() > 0;
    }

    /**
     * Get user's wishlist with product details.
     */
    public static function getUserWishlist($userId)
    {
        return self::with('product') // Eager load product details
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get the count of items in user's wishlist.
     */
    public static function getWishlistCount($userId): int
    {
        return self::where('user_id', $userId)->count();
    }
}
