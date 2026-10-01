<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVoucher extends Model
{
    protected $fillable = [
        'user_id',
        'promotion_id',
        'is_used',
        'used_at',
        'booking_id',
        'saved_at',
    ];

    protected function casts(): array
    {
        return [
            'is_used' => 'boolean',
            'used_at' => 'datetime',
            'saved_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_used', false)
            ->whereHas('promotion', function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNull('end_date')
                        ->orWhere('end_date', '>=', now()->toDateString());
                });
            });
    }

    public function scopeUsed($query)
    {
        return $query->where('is_used', true);
    }
}
