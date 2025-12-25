<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'duration',
        'price_standard',
        'price_fullboard_adult',
        'price_fullboard_child',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price_standard' => 'decimal:2',
        'price_fullboard_adult' => 'decimal:2',
        'price_fullboard_child' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get all bookings for this package
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scope to only active packages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for standard packages
     */
    public function scopeStandard($query)
    {
        return $query->where('name', 'Standard');
    }

    /**
     * Scope for full board packages
     */
    public function scopeFullBoard($query)
    {
        return $query->where('name', 'Full Board');
    }
}
