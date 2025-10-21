<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'parent_id'
    ];

    public function getFirstProductImage()
{
    // 1. Check this category's products
    foreach ($this->products as $product) {
        if ($product->primaryImage) {
            return $product->primaryImage->url;
        } elseif ($product->images->first()) {
            return $product->images->first()->url;
        }
    }

    // 2. Recursively check children
    foreach ($this->children as $child) {
        $image = $child->getFirstProductImage();
        if ($image) {
            return $image;
        }
    }

    // 3. Nothing found
    return null;
}

    /**
     * Use slug for route-model binding.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug when creating
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = static::generateUniqueSlug($category->name);
            }
        });

        // Auto-update slug if name changes
        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = static::generateUniqueSlug($category->name, $category->id);
            }
        });
    }

    /**
     * Generate a unique slug from name.
     */
    public static function generateUniqueSlug($name, $ignoreId = null)
    {
        $base = Str::slug($name ?: 'category');
        $slug = $base;
        $i = 1;

        while (static::query()
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    public function getParentTree()
    {
        $parents = [];
        $category = $this;

        while ($category->parent) {
            $parents[] = $category->parent->name;
            $category = $category->parent;
        }

        return array_reverse($parents); // so it starts from root -> child
    }


    // Relationships

    /**
     * A category can have many children (subcategories).
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * A category may belong to a parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * A category can have many products.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
