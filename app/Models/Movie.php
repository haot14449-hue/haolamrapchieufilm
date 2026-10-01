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

    /**
     * Relationship to upcoming showtimes that have not yet started.
     */
    public function upcomingShowtimes()
    {
        return $this->hasMany(Showtime::class)
                    ->where('start_time', '>=', now())
                    ->orderBy('start_time', 'asc');
    }

    public function actors()
    {
        return $this->hasMany(MovieActor::class)->orderBy('order', 'asc');
    }

    public function topHot()
    {
        return $this->hasOne(TopHotMovie::class);
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
