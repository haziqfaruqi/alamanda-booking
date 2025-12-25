<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'guest_name',
        'guest_ic',
        'guest_type',
        'age',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'age' => 'integer',
    ];

    /**
     * Get the booking for the guest
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope for adult guests
     */
    public function scopeAdults($query)
    {
        return $query->where('guest_type', 'adult');
    }

    /**
     * Scope for child guests
     */
    public function scopeChildren($query)
    {
        return $query->where('guest_type', 'child');
    }

    /**
     * Check if guest is adult
     */
    public function isAdult(): bool
    {
        return $this->guest_type === 'adult';
    }

    /**
     * Check if guest is child
     */
    public function isChild(): bool
    {
        return $this->guest_type === 'child';
    }
}
