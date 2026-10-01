<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'original_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'points_used' => 'integer',
            'points_earned' => 'integer',
            'points_processed' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Check if 5-minute hold time has expired.
     */
    public function isExpired(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }
        if ($this->expires_at) {
            return now()->greaterThan($this->expires_at);
        }
        return $this->created_at ? now()->greaterThan($this->created_at->addMinutes(5)) : false;
    }

    /**
     * Get remaining hold time in seconds (for countdown timer).
     */
    public function getRemainingSecondsAttribute(): int
    {
        if ($this->status !== 'pending') {
            return 0;
        }
        $expireTime = $this->expires_at ?? ($this->created_at ? $this->created_at->addMinutes(5) : now());
        return max(0, (int) now()->diffInSeconds($expireTime, false));
    }

    /**
     * Release held seats back to available/empty state.
     */
    public function releaseSeats(): void
    {
        $this->status = 'cancelled';
        $this->save();
        $this->tickets()->update(['status' => 'cancelled']);
    }

    /**
     * Clean up all pending bookings that exceeded the 5-minute hold time and release seats.
     */
    public static function cleanupExpired(?int $showtimeId = null): int
    {
        $query = static::where('status', 'pending')
            ->where(function ($q) {
                $q->where('expires_at', '<', now())
                  ->orWhere(function ($sub) {
                      $sub->whereNull('expires_at')
                          ->where('created_at', '<', now()->subMinutes(5));
                  });
            });

        if ($showtimeId) {
            $query->where('showtime_id', $showtimeId);
        }

        $expired = $query->get();
        $count = 0;
        foreach ($expired as $booking) {
            $booking->releaseSeats();
            $count++;
        }

        return $count;
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function foods()
    {
        return $this->belongsToMany(Food::class, 'booking_food')->withPivot('quantity', 'price');
    }

    /**
     * Get Vietnamese label for booking status.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'paid' => 'Đã thanh toán',
            'pending' => 'Chờ thanh toán',
            'cancelled' => 'Đã hủy',
            default => ucfirst($this->status ?? 'Không xác định'),
        };
    }
}
