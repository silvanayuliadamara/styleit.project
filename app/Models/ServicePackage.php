<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServicePackage extends Model
{
    protected $fillable = [
        'category_id', 'code', 'name', 'slug', 'description', 'price', 'dp_amount',
        'quota_per_day', 'is_popular', 'image', 'sort_order',
        'butuh_makeup', 'butuh_baju', 'softlens_wajib_pilih', 'status',
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

    public function getItemsAttribute()
    {
        $items = $this->relationLoaded('items') ? $this->getRelation('items') : $this->items()->get();
        $items = collect($items->all());
        
        if ($this->category_id == 3) {
            $freeSoftlens = new PackageItem([
                'package_id' => $this->id,
                'name' => 'Free Softlens',
                'quantity' => 1,
                'unit' => 'x'
            ]);
            $items->push($freeSoftlens);
        }
        return $items;
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'package_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'package_id');
    }

    public function getNameAttribute($value)
    {
        if ($this->category_id == 3) {
            return 'Wisuda/Bridesmaid';
        }
        $name = trim(str_ireplace('Jasa ', '', $value));
        $name = preg_replace('/\s+-\s+[AB]$/i', '', $name);
        $name = preg_replace('/\s+[AB]$/i', '', $name);
        return $name;
    }

    public function getDescriptionAttribute($value)
    {
        if ($this->category_id == 3) {
            return '';
        }
        return $value;
    }

    public function getDpAmountAttribute($value)
    {
        $categorySlug = $this->relationLoaded('category') ? ($this->category->slug ?? '') : '';
        if ($categorySlug === '') {
            $slugs = [1 => 'wedding', 2 => 'prewedding', 3 => 'regular', 4 => 'baju'];
            $categorySlug = $slugs[$this->category_id] ?? '';
        }

        if ($categorySlug === 'wedding') {
            if ($this->butuh_makeup && $this->butuh_baju) {
                return 1000000;
            } else {
                return 500000;
            }
        } elseif ($categorySlug === 'regular') {
            return 200000;
        } elseif (in_array($categorySlug, ['prewedding', 'baju'])) {
            return 500000;
        }
        return $value;
    }

    public function getIsBestSellerAttribute()
    {
        return \Cache::remember('best_seller_package_id', 60, function () {
            return \App\Models\Booking::select('package_id')
                ->groupBy('package_id')
                ->orderByRaw('COUNT(*) DESC')
                ->value('package_id');
        }) == $this->id;
    }
}
