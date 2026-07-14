<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    /** Default time ranges for each slot type. */
    const SLOT_DEFAULTS = [
        'pagi'  => ['start' => '06:00', 'end' => '11:00'],
        'siang' => ['start' => '12:00', 'end' => '16:00'],
        'sore'  => ['start' => '16:30', 'end' => '20:30'],
        'baju'  => ['start' => '08:00', 'end' => '17:00'],
    ];

    protected $fillable = [
        'category_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'jenis_jadwal',
        'kuota',
        'terpakai',
        'status',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'kuota' => 'integer',
        'terpakai' => 'integer',
    ];

    /* ---- Relationships ---- */

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /* ---- Scopes ---- */

    public function scopeForCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeForDate($query, string $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    public function scopeForSlot($query, string $jenisJadwal)
    {
        return $query->where('jenis_jadwal', $jenisJadwal);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeBlocked($query)
    {
        return $query->where('status', 'diblokir');
    }

    /* ---- Helpers ---- */

    public function isTersedia(): bool
    {
        return $this->status === 'tersedia' && $this->terpakai < $this->kuota;
    }

    public function isPenuh(): bool
    {
        return $this->status === 'penuh' || $this->terpakai >= $this->kuota;
    }

    public function isDiblokir(): bool
    {
        return $this->status === 'diblokir';
    }

    public function sisaKuota(): int
    {
        return max(0, $this->kuota - $this->terpakai);
    }

    /**
     * Tambah terpakai +1, otomatis set 'penuh' jika kuota habis.
     */
    public function incrementTerpakai(): void
    {
        $this->increment('terpakai');

        if ($this->terpakai >= $this->kuota) {
            $this->update(['status' => 'penuh']);
        }
    }

    /**
     * Kurangi terpakai -1, otomatis kembalikan ke 'tersedia' jika sebelumnya penuh.
     */
    public function decrementTerpakai(): void
    {
        if ($this->terpakai > 0) {
            $this->decrement('terpakai');
        }

        if ($this->status === 'penuh' && $this->terpakai < $this->kuota) {
            $this->update(['status' => 'tersedia']);
        }
    }
}
