<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lapangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_olahraga_id',
        'nama_lapangan',
        'harga_per_jam',
        'deskripsi',
        'gambar',
        'jam_buka',
        'jam_tutup',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'jam_buka' => 'datetime:H:i',
            'jam_tutup' => 'datetime:H:i',
        ];
    }

    public function jenisOlahraga(): BelongsTo
    {
        return $this->belongsTo(JenisOlahraga::class, 'jenis_olahraga_id');
    }

    public function gambarTambahan(): HasMany
    {
        return $this->hasMany(LapanganGambar::class, 'lapangan_id');
    }

    public function fasilitas(): BelongsToMany
    {
        return $this->belongsToMany(Fasilitas::class, 'lapangan_fasilitas', 'lapangan_id', 'fasilitas_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
