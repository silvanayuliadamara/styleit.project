<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CancellationRequest extends Model
{
    protected $fillable = [
        'booking_id',
        'alasan',
        'status_persetujuan',
        'approved_by',
        'customer_dibaca',
        'dp_dikembalikan',
        'jumlah_dp_dikembalikan',
    ];

    protected $casts = [
        'customer_dibaca' => 'boolean',
        'dp_dikembalikan' => 'boolean',
        'jumlah_dp_dikembalikan' => 'integer',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
