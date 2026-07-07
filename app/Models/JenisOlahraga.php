<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisOlahraga extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function lapangan(): HasMany
    {
        return $this->hasMany(Lapangan::class);
    }
}
