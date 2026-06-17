<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServicePackage extends Model
{
    protected $fillable = [
        'category_id', 'code', 'name', 'slug', 'description', 'price', 'dp_amount',
        'quota_per_day', 'is_popular', 'image', 'sort_order',
        'butuh_makeup', 'butuh_baju', 'softlens_wajib_pilih', 'status'
    ];

    protected $casts = [
        'price' => 'integer',
        'dp_amount' => 'integer',
        'quota_per_day' => 'integer',
        'is_popular' => 'boolean',
        'butuh_makeup' => 'boolean',
        'butuh_baju' => 'boolean',
        'softlens_wajib_pilih' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PackageItem::class, 'package_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'package_id');
    }
}
