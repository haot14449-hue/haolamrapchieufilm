<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'code',
        'title',
        'description',
        'discount_percent',
        'discount_amount',
        'points_required',
        'start_date',
        'end_date',
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
