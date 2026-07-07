<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';

    protected $fillable = ['name'];

    public function lapangan(): BelongsToMany
    {
        return $this->belongsToMany(Lapangan::class, 'lapangan_fasilitas', 'fasilitas_id', 'lapangan_id');
    }
}
