<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pembayaran;
use App\Services\MidtransPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function show(Request $request, Booking $booking, MidtransPaymentService $midtransPaymentService): View|RedirectResponse
    {
        abort_unless($request->user()->id === $booking->user_id, 403);

        $booking->load(['user', 'lapangan.jenisOlahraga', 'pembayaran']);

        $payment = $booking->pembayaran;
        $paymentConfigured = $midtransPaymentService->isConfigured();

        if (! $payment) {
            $payment = Pembayaran::create([
                'booking_id' => $booking->id,
                'jumlah' => $booking->total_harga,
                'status' => 'pending',
            ]);
        }

        if ($paymentConfigured && ! $payment->snap_token && in_array($payment->status, ['pending', 'gagal'], true)) {
            try {
                $payment->update([
                    'midtrans_order_id' => $payment->midtrans_order_id ?: $midtransPaymentService->makeOrderId($payment),
                    'snap_token' => $midtransPaymentService->createSnapToken($booking, $payment),
                ]);
            } catch (\Throwable $throwable) {
                Log::error('Gagal membuat Snap token Midtrans', [
                    'booking_id' => $booking->id,
                    'payment_id' => $payment->id,
                    'message' => $throwable->getMessage(),
                ]);
            }
        }

        return view('customer.payment-show', compact('booking', 'payment', 'paymentConfigured'));
    }

    public function notification(Request $request): JsonResponse
    {
        $serverKey = config('midtrans.server_key');

        if (! $serverKey) {
            return response()->json(['message' => 'Midtrans belum dikonfigurasi.'], 500);
        }

        $signatureKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        abort_unless($signatureKey === $request->signature_key, 403);

        $payment = Pembayaran::where('midtrans_order_id', $request->order_id)->firstOrFail();

        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status;

        $payment->update([
            'transaction_id' => $request->transaction_id,
            'transaction_status' => $transactionStatus,
            'payment_type' => $request->payment_type,
            'fraud_status' => $fraudStatus,
            'raw_response' => $request->all(),
        ]);

        if ($transactionStatus === 'capture' && $fraudStatus === 'challenge') {
            $payment->update(['status' => 'pending']);
        } elseif (in_array($transactionStatus, ['capture', 'settlement'], true)) {
            $payment->update([
                'status' => 'berhasil',
                'paid_at' => now(),
            ]);

            $payment->booking()->update([
                'status' => 'confirmed',
            ]);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'], true)) {
            $payment->update(['status' => 'gagal']);

            $payment->booking()->update([
                'status' => 'cancelled',
            ]);
        } else {
            $payment->update(['status' => 'pending']);
        }

        return response()->json(['status' => 'ok']);
    }
}