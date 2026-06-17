<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'is_active',
        'harga_default',
        'is_pihak_lain',
        'biaya_pihak_lain',
        'status'
    ];

    protected $casts = [
        'price' => 'integer',
        'is_active' => 'boolean',
        'harga_default' => 'integer',
        'is_pihak_lain' => 'boolean',
        'biaya_pihak_lain' => 'integer',
    ];

    public function categories()
    {
        return $this->belongsToMany(ServiceCategory::class, 'category_addons', 'addon_id', 'category_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function options()
    {
        return $this->hasMany(AddonOption::class);
    }
}
