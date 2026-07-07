<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pembayaran;
use App\Services\MidtransPaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create(Request $request, Lapangan $lapangan)
    {
        $lapangan->load(['jenisOlahraga', 'fasilitas']);

        $selectedDate = Carbon::parse($request->query('tanggal', now()->toDateString()))
            ->toDateString();

        $timeSlots = $this->buildTimeSlots($lapangan, $selectedDate);
        $bookedCount = collect($timeSlots)->where('blocked', true)->count();

        return view('customer.booking-create', compact(
            'lapangan',
            'selectedDate',
            'timeSlots',
            'bookedCount'
        ));
    }

    public function store(Request $request, Lapangan $lapangan, MidtransPaymentService $midtransPaymentService)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ]);

        $tanggal = Carbon::parse($validated['tanggal'])->toDateString();
        $jamMulai = $validated['jam_mulai'];
        $jamSelesai = $validated['jam_selesai'];

        if (Booking::isBentrok($lapangan->id, $tanggal, $jamMulai, $jamSelesai)) {
            return back()
                ->withInput()
                ->withErrors([
                    'jam_mulai' => 'Slot waktu ini sudah dibooking. Silakan pilih jam lain.',
                ]);
        }

        $durasiMenit = Carbon::createFromFormat('H:i', $jamMulai)
            ->diffInMinutes(Carbon::createFromFormat('H:i', $jamSelesai));

        $durasiJam = max(1, (int) ceil($durasiMenit / 60));
        $totalHarga = $durasiJam * $lapangan->harga_per_jam;

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'lapangan_id' => $lapangan->id,
            'tanggal' => $tanggal,
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'total_harga' => $totalHarga,
            'status' => 'pending',
        ]);

        $payment = Pembayaran::create([
            'booking_id' => $booking->id,
            'jumlah' => $totalHarga,
            'status' => 'pending',
        ]);

        if ($midtransPaymentService->isConfigured()) {
            try {
                $payment->update([
                    'midtrans_order_id' => $midtransPaymentService->makeOrderId($payment),
                    'snap_token' => $midtransPaymentService->createSnapToken($booking, $payment),
                ]);
            } catch (\Throwable $throwable) {
                report($throwable);
            }
        }

        return redirect()
            ->route('customer.payments.show', $booking)
            ->with('success', 'Booking berhasil dibuat. Silakan selesaikan pembayaran melalui Midtrans.');
    }

    private function buildTimeSlots(Lapangan $lapangan, string $selectedDate): array
    {
        $start = Carbon::parse($lapangan->jam_buka)->copy();
        $end = Carbon::parse($lapangan->jam_tutup)->copy();
        $slots = [];

        $cursor = $start->copy();

        while ($cursor->copy()->addHour()->lte($end)) {
            $slotEnd = $cursor->copy()->addHour();
            $blocked = Booking::isBentrok(
                $lapangan->id,
                $selectedDate,
                $cursor->format('H:i'),
                $slotEnd->format('H:i')
            );

            $slots[] = [
                'value' => $cursor->format('H:i'),
                'end_value' => $slotEnd->format('H:i'),
                'label' => $cursor->format('H:i'),
                'blocked' => $blocked,
            ];

            $cursor->addHour();
        }

        return $slots;
    }
}