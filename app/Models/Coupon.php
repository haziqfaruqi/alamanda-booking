<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'description',
        'min_guests',
        'max_uses',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
    ];

    /**
     * Check if coupon is valid
     */
    public function isValid(int $guestCount = 0): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($now->lt($this->valid_from) || $now->gt($this->valid_until)) {
            return false;
        }

        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            return false;
        }

        if ($guestCount < $this->min_guests) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount(float $totalAmount): float
    {
        if ($this->type === 'percentage') {
            return $totalAmount * ($this->value / 100);
        }

        return min($this->value, $totalAmount);
    }

    /**
     * Increment used count
     */
    public function incrementUsedCount(): void
    {
        $this->increment('used_count');
    }

    /**
     * Get all bookings that used this coupon
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
