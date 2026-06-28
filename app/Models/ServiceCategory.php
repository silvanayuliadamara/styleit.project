<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceCategory extends Model
{
    protected $fillable = ['name', 'slug', 'headline', 'description', 'icon', 'sort_order'];

    public function packages(): HasMany
    {
        return $this->hasMany(ServicePackage::class, 'category_id');
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class, 'category_addons', 'category_id', 'addon_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Find a category by its slug, cached per-request for performance.
     */
    public static function findBySlug(string $slug): ?self
    {
        static $cache = [];

        if (!isset($cache[$slug])) {
            $cache[$slug] = self::where('slug', $slug)->first();
        }

        return $cache[$slug];
    }

    /**
     * Get IDs for Wedding + Prewedding categories (commonly used together).
     */
    public static function getWeddingCategoryIds(): array
    {
        return array_filter([
            self::findBySlug('wedding')?->id,
            self::findBySlug('prewedding')?->id,
        ]);
    }
}
