<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'phone', 'points', 'avatar_url'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'points' => 'integer',
        ];
    }

    /**
     * Check if user has uploaded an avatar.
     */
    public function hasAvatar(): bool
    {
        return !empty($this->avatar_url);
    }

    /**
     * Get user's wallet vouchers.
     */
    public function userVouchers(): HasMany
    {
        return $this->hasMany(UserVoucher::class);
    }

    /**
     * Get saved promotions through user vouchers.
     */
    public function savedPromotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class, 'user_vouchers')
                    ->withPivot(['id', 'is_used', 'used_at', 'booking_id', 'saved_at'])
                    ->withTimestamps();
    }

    /**
     * Get active/available vouchers count in wallet.
     */
    public function activeVouchersCount(): int
    {
        return $this->userVouchers()
            ->where('is_used', false)
            ->whereHas('promotion', function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNull('end_date')
                        ->orWhere('end_date', '>=', now()->toDateString());
                });
            })
            ->count();
    }
}
