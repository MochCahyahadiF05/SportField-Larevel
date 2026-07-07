<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $bookings = Booking::with(['user', 'lapangan.jenisOlahraga'])
            ->latest()
            ->get();

        $pendingBookings = $bookings->where('status', 'pending')->count();
        $confirmedBookings = $bookings->where('status', 'confirmed')->count();
        $completedBookings = $bookings->where('status', 'completed')->count();
        $cancelledBookings = $bookings->where('status', 'cancelled')->count();

        return view('admin.booking.index', compact(
            'bookings',
            'pendingBookings',
            'confirmedBookings',
            'completedBookings',
            'cancelledBookings'
        ));
    }

    public function confirm(Booking $booking): RedirectResponse
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking hanya bisa dikonfirmasi dari status pending.');
        }

        $booking->update(['status' => 'confirmed']);

        return redirect()
            ->route('admin.booking.index')
            ->with('success', 'Booking berhasil dikonfirmasi.');
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        if (! in_array($booking->status, ['pending', 'confirmed'], true)) {
            return back()->with('error', 'Booking ini tidak bisa dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()
            ->route('admin.booking.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }

    public function complete(Booking $booking): RedirectResponse
    {
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Booking hanya bisa diselesaikan dari status confirmed.');
        }

        $booking->update(['status' => 'completed']);

        return redirect()
            ->route('admin.booking.index')
            ->with('success', 'Booking berhasil diselesaikan.');
    }
}