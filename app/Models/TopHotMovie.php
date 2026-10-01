<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopHotMovie extends Model
{
    use HasFactory;

    protected $table = 'top_hot_movies';

    protected $fillable = [
        'movie_id',
        'rank',
        'sub_title',
        'badge_text',
        'badge_text_2',
        'age_rating',
        'custom_poster',
        'is_active',
    ];

    protected $casts = [
        'rank' => 'integer',
        'is_active' => 'boolean',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('rank', 'asc');
    }

    public function getDisplayPosterAttribute()
    {
        if ($this->custom_poster) {
            if (str_starts_with($this->custom_poster, 'http://') || str_starts_with($this->custom_poster, 'https://')) {
                return $this->custom_poster;
            }
            return asset($this->custom_poster);
        }

        return $this->movie ? $this->movie->poster_url : '';
    }

    public function getDisplaySubTitleAttribute()
    {
        if (!empty($this->sub_title)) {
            return $this->sub_title;
        }

        return $this->movie ? $this->movie->genre : '';
    }
}
