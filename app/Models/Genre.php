<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Automatically generate slug if not provided.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($genre) {
            if (empty($genre->slug)) {
                $genre->slug = Str::slug($genre->name);
            }
        });

        static::updating(function ($genre) {
            if ($genre->isDirty('name') && !$genre->isDirty('slug')) {
                $genre->slug = Str::slug($genre->name);
            }
        });
    }

    /**
     * Get count of movies matching this genre.
     */
    public function getMoviesCountAttribute(): int
    {
        return Movie::where('genre', 'like', '%' . $this->name . '%')->count();
    }
}
