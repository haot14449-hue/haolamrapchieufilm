<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'poster_url',
        'backdrop_url',
        'trailer_url',
        'duration',
        'release_date',
        'genre',
    ];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }

    public function actors()
    {
        return $this->hasMany(MovieActor::class)->orderBy('order', 'asc');
    }

    public function getPosterUrlAttribute($value)
    {
        if (!$value) {
            return '';
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }
        return asset($value);
    }

    public function getBackdropUrlAttribute($value)
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
