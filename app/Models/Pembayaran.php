<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'jumlah',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status',
        'paid_at',
        'midtrans_order_id',
        'snap_token',
        'transaction_id',
        'transaction_status',
        'payment_type',
        'fraud_status',
        'raw_response',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'raw_response' => 'array',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
