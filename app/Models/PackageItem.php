<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageItem extends Model
{
    protected $fillable = [
        'package_id', 'name', 'quantity', 'unit',
        'is_pihak_lain', 'biaya_pihak_lain', 'keterangan'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'is_pihak_lain' => 'boolean',
        'biaya_pihak_lain' => 'integer',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(ServicePackage::class, 'package_id');
    }
}
