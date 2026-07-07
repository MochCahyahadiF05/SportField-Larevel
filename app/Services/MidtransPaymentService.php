<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Snap;
use RuntimeException;

class MidtransPaymentService
{
    public function isConfigured(): bool
    {
        return (bool) config('midtrans.server_key') && (bool) config('midtrans.client_key');
    }

    public function configure(): void
    {
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');

        if (! $serverKey || ! $clientKey) {
            throw new RuntimeException('Konfigurasi Midtrans belum lengkap.');
        }

        Config::$serverKey = $serverKey;
        Config::$clientKey = $clientKey;
        Config::$isProduction = (bool) config('midtrans.is_production', false);
        Config::$isSanitized = (bool) config('midtrans.is_sanitized', true);
        Config::$is3ds = (bool) config('midtrans.is_3ds', true);
    }

    public function makeOrderId(Pembayaran $payment): string
    {
        return 'SPF-' . $payment->booking_id . '-' . $payment->id . '-' . now()->format('YmdHis');
    }

    public function createSnapToken(Booking $booking, Pembayaran $payment): string
    {
        $this->configure();

        if (! $payment->midtrans_order_id) {
            $payment->update([
                'midtrans_order_id' => $this->makeOrderId($payment),
            ]);
        }

        $booking->loadMissing(['user', 'lapangan']);

        $payload = [
            'transaction_details' => [
                'order_id' => $payment->midtrans_order_id,
                'gross_amount' => (int) $payment->jumlah,
            ],
            'customer_details' => [
                'first_name' => $booking->user?->name ?? 'Customer',
                'email' => $booking->user?->email ?? 'customer@example.com',
                'phone' => $booking->user?->phone,
            ],
            'item_details' => [
                [
                    'id' => 'booking-' . $booking->id,
                    'price' => (int) $payment->jumlah,
                    'quantity' => 1,
                    'name' => 'Booking ' . ($booking->lapangan?->nama_lapangan ?? 'Lapangan'),
                ],
            ],
        ];

        return Snap::getSnapToken($payload);
    }
}