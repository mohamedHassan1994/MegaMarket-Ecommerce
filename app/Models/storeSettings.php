<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'facebook',
        'instagram',
        'linkedin',
        'youtube',
        'twitter',
        'tiktok',
        'pinterest',
    ];

    // Polymorphic relationship for images
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    // Helper methods to get specific images
    public function websiteLogo()
    {
        return $this->images()->where('image_type', 'website_logo')->first();
    }

    public function favicon()
    {
        return $this->images()->where('image_type', 'favicon')->first();
    }

    public function footerLogo()
    {
        return $this->images()->where('image_type', 'footer_logo')->first();
    }

    public function homeBanner()
    {
        return $this->images()->where('image_type', 'home_banner')->first();
    }
}
