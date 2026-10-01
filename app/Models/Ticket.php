<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    /**
     * Get Vietnamese label for ticket status.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'booked' => 'Đã đặt chỗ',
            'paid' => 'Đã thanh toán',
            'cancelled' => 'Đã hủy',
            default => ucfirst($this->status ?? 'Không xác định'),
        };
    }
}
