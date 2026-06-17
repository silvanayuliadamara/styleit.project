<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddonOption extends Model
{
    protected $fillable = [
        'addon_id',
        'nama_option',
        'tipe_option',
        'harga',
        'is_pihak_lain',
        'biaya_pihak_lain',
        'status',
    ];

    protected $casts = [
        'harga' => 'integer',
        'is_pihak_lain' => 'boolean',
        'biaya_pihak_lain' => 'integer',
    ];

    public function addon(): BelongsTo
    {
        return $this->belongsTo(Addon::class);
    }
}
