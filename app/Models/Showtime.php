<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'room_id',
        'start_time',
        'price',
        'format',
        'language',
    ];

    protected $casts = [
        'start_time' => 'datetime',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Scope to filter showtimes that have not yet started.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>=', now());
    }

    /**
     * Scope to filter showtimes that have already passed.
     */
    public function scopePast($query)
    {
        return $query->where('start_time', '<', now());
    }

    /**
     * Check if this showtime has already passed.
     */
    public function isPast(): bool
    {
        return \Carbon\Carbon::parse($this->start_time)->isPast();
    }

    /**
     * Check if this showtime is still upcoming.
     */
    public function isUpcoming(): bool
    {
        return !$this->isPast();
    }
}
