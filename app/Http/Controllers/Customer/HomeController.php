<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;

class HomeController extends Controller
{
    public function index()
    {
        $lapanganUnggulan = Lapangan::with(['jenisOlahraga', 'fasilitas'])
            ->where('status', 'tersedia')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('reviews_count')
            ->latest()
            ->take(3)
            ->get();

        return view('customer.home', compact('lapanganUnggulan'));
    }
}