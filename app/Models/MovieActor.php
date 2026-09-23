<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieActor extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'name',
        'role',
        'avatar',
        'bio',
        'order',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    /**
     * Get the formatted avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!$this->avatar) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1f2937&color=f59e0b&size=200';
        }

        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        return asset($this->avatar);
    }
}
