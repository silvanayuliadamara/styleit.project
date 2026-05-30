<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'booking_code', 'user_id', 'package_id', 'booking_date', 'softlens', 'subtotal',
        'addon_total', 'total_price', 'dp_amount', 'remaining_payment', 'status',
        'payment_status', 'notes'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'softlens' => 'boolean',
        'subtotal' => 'integer',
        'addon_total' => 'integer',
        'total_price' => 'integer',
        'dp_amount' => 'integer',
        'remaining_payment' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(ServicePackage::class, 'package_id');
    }

    public function addons(): BelongsToMany
    {
        return $this->belongsToMany(Addon::class, 'booking_addons')
            ->withPivot(['price'])
            ->withTimestamps();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
