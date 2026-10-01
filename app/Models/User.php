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

#[Fillable(['name', 'email', 'password', 'role', 'phone', 'birthday', 'points', 'avatar_url', 'email_verified_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'birthday',
        'points',
        'avatar_url',
        'email_verified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birthday' => 'date',
            'password' => 'hashed',
            'points' => 'integer',
        ];
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is staff.
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Check if user can access POS ticket sales counter.
     * Only staff and admin can access POS. Customers CANNOT.
     */
    public function canAccessPos(): bool
    {
        return in_array($this->role, ['admin', 'staff']);
    }

    /**
     * Check if user can access Admin area.
     * ONLY admin can access admin area. Staff and Customers CANNOT.
     */
    public function canAccessAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Helper for accessing staff-level features (POS).
     */
    public function canAccessStaffPanel(): bool
    {
        return $this->canAccessPos();
    }

    /**
     * Check if user is customer/regular user.
     */
    public function isCustomer(): bool
    {
        return in_array($this->role, ['customer', 'user']) || empty($this->role);
    }

    /**
     * Get human-readable role name.
     */
    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Quản trị viên',
            'staff' => 'Nhân viên',
            default => 'Khách hàng',
        };
    }

    /**
     * Get role badge CSS classes.
     */
    public function getRoleBadgeClassAttribute(): string
    {
        return match($this->role) {
            'admin' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'staff' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            default => 'bg-blue-50 text-blue-700 border-blue-200',
        };
    }

    /**
     * Check if user has uploaded an avatar.
     */
    public function hasAvatar(): bool
    {
        return !empty($this->avatar_url);
    }

    /**
     * Get user's bookings.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
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
