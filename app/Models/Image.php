<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'is_primary',
        'imageable_id',
        'imageable_type',
        'image_type',
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
}
