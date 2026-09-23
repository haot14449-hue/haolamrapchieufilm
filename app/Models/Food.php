<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'food';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
    ];

    /**
     * Format image URL to support both local uploads and external links.
     */
    public function getImageUrlAttribute($value): string
    {
        if (!$value) {
            return '';
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return asset($value);
    }
}
