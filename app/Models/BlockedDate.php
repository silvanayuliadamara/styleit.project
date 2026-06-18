<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedDate extends Model
{
    protected $fillable = ['blocked_date', 'reason'];

    protected $casts = ['blocked_date' => 'date'];
}
