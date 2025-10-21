<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'input_type', 'is_required', 'sort_order'];

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
