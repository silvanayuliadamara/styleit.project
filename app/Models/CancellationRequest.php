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
    ];

    protected $casts = [
        'customer_dibaca' => 'boolean',
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
