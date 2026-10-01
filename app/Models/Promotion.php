<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Promotion extends Model
{
    protected $fillable = [
        'code',
        'title',
        'description',
        'discount_percent',
        'discount_amount',
        'points_required',
        'start_date',
        'end_date',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'discount_amount' => 'decimal:2',
            'discount_percent' => 'integer',
            'points_required' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function userVouchers(): HasMany
    {
        return $this->hasMany(UserVoucher::class);
    }

    /**
     * Check if a specific user has already saved this voucher to wallet.
     */
    public function isSavedByUser(?int $userId): bool
    {
        if (!$userId) return false;
        return $this->userVouchers()->where('user_id', $userId)->exists();
    }

    /**
     * Check if the promotion is expired.
     */
    public function isExpired(): bool
    {
        if (!$this->end_date) return false;
        return Carbon::parse($this->end_date)->endOfDay()->isPast();
    }

    /**
     * Check if the promotion is not yet started.
     */
    public function isUpcoming(): bool
    {
        if (!$this->start_date) return false;
        return Carbon::parse($this->start_date)->startOfDay()->isFuture();
    }

    /**
     * Check if the promotion is currently active/valid.
     */
    public function isActive(): bool
    {
        return !$this->isExpired() && !$this->isUpcoming();
    }

    /**
     * Scope a query to only include active/valid promotions (not expired and already started).
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', now()->toDateString());
        })->where(function ($q) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', now()->toDateString());
        });
    }

    /**
     * Formatted discount text for UI badges.
     */
    public function formattedDiscount(): string
    {
        if ($this->discount_percent) {
            return "-{$this->discount_percent}%";
        }
        if ($this->discount_amount) {
            return "-" . number_format($this->discount_amount, 0, ',', '.') . " đ";
        }
        return "Ưu Đãi";
    }

    /**
     * Format image URL to support both local uploads and external links.
     */
    public function getImageUrlAttribute($value): string
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
