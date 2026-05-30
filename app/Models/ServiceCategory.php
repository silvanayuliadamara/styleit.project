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
}
