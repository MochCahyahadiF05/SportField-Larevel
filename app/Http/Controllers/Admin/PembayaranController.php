<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    public function index(): View
    {
        $pembayarans = Pembayaran::with(['booking.user', 'booking.lapangan.jenisOlahraga'])
            ->latest()
            ->get();

        $pendingPembayaran = $pembayarans->where('status', 'pending')->count();
        $berhasilPembayaran = $pembayarans->where('status', 'berhasil')->count();
        $gagalPembayaran = $pembayarans->where('status', 'gagal')->count();

        return view('admin.pembayaran.index', compact(
            'pembayarans',
            'pendingPembayaran',
            'berhasilPembayaran',
            'gagalPembayaran'
        ));
    }
}