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
     * Scope to filter showtimes that have not yet ended (up to 4 hours after start time).
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>=', now()->subHours(4));
    }

    /**
     * Scope to filter showtimes that have already passed (more than 4 hours ago).
     */
    public function scopePast($query)
    {
        return $query->where('start_time', '<', now()->subHours(4));
    }

    /**
     * Check if this showtime has already passed its active booking window (4 hours buffer).
     */
    public function isPast(): bool
    {
        return \Carbon\Carbon::parse($this->start_time)->addHours(4)->isPast();
    }

    /**
     * Check if this showtime has already started.
     */
    public function isStarted(): bool
    {
        return \Carbon\Carbon::parse($this->start_time)->isPast();
    }

    /**
     * Check if this showtime is still upcoming or active.
     */
    public function isUpcoming(): bool
    {
        return !$this->isPast();
    }
}
