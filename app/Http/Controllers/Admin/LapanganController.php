<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use App\Models\JenisOlahraga;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::with(['jenisOlahraga', 'fasilitas'])
            ->latest()
            ->get();

        $totalLapangan = $lapangans->count();
        $lapanganAktif = $lapangans->where('status', 'tersedia')->count();
        $dalamPerbaikan = $lapangans->where('status', 'perbaikan')->count();
        $jenisOlahragaOptions = JenisOlahraga::orderBy('name')->get();
        $fasilitasOptions = Fasilitas::orderBy('name')->get();

        return view('admin.lapangan.index', compact(
            'lapangans',
            'totalLapangan',
            'lapanganAktif',
            'dalamPerbaikan',
            'jenisOlahragaOptions',
            'fasilitasOptions'
        ));
    }

    public function create()
    {
        $jenisOlahragas = JenisOlahraga::orderBy('name')->get();
        $fasilitas = Fasilitas::orderBy('name')->get();

        return view('admin.lapangan.create', compact('jenisOlahragas', 'fasilitas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_olahraga_id' => ['required', 'exists:jenis_olahragas,id'],
            'nama_lapangan' => ['required', 'string', 'max:255'],
            'harga_per_jam' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'jam_buka' => ['required', 'date_format:H:i'],
            'jam_tutup' => ['nullable', 'date_format:H:i'],
            'status' => ['required', 'in:tersedia,perbaikan'],
            'fasilitas_ids' => ['nullable', 'array'],
            'fasilitas_ids.*' => ['integer', 'exists:fasilitas,id'],
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('lapangan', 'public');
        }

        $fasilitasIds = $data['fasilitas_ids'] ?? [];
        unset($data['fasilitas_ids']);
        $data['jam_tutup'] = $data['jam_tutup'] ?? '23:00';

        $lapangan = Lapangan::create($data);
        $lapangan->fasilitas()->sync($fasilitasIds);

        return redirect()
            ->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(Lapangan $lapangan)
    {
        $lapangan->load('fasilitas');

        $jenisOlahragas = JenisOlahraga::orderBy('name')->get();
        $fasilitas = Fasilitas::orderBy('name')->get();

        return view('admin.lapangan.edit', compact('lapangan', 'jenisOlahragas', 'fasilitas'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $data = $request->validate([
            'jenis_olahraga_id' => ['required', 'exists:jenis_olahragas,id'],
            'nama_lapangan' => ['required', 'string', 'max:255'],
            'harga_per_jam' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'jam_buka' => ['required', 'date_format:H:i'],
            'jam_tutup' => ['nullable', 'date_format:H:i'],
            'status' => ['required', 'in:tersedia,perbaikan'],
            'fasilitas_ids' => ['nullable', 'array'],
            'fasilitas_ids.*' => ['integer', 'exists:fasilitas,id'],
        ]);

        if ($request->hasFile('gambar')) {
            if ($lapangan->gambar) {
                Storage::disk('public')->delete($lapangan->gambar);
            }

            $data['gambar'] = $request->file('gambar')->store('lapangan', 'public');
        } else {
            unset($data['gambar']);
        }

        $fasilitasIds = $data['fasilitas_ids'] ?? [];
        unset($data['fasilitas_ids']);
        $data['jam_tutup'] = $data['jam_tutup'] ?? $lapangan->jam_tutup;

        $lapangan->update($data);
        $lapangan->fasilitas()->sync($fasilitasIds);

        return redirect()
            ->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroy(Lapangan $lapangan)
    {
        if ($lapangan->gambar) {
            Storage::disk('public')->delete($lapangan->gambar);
        }

        $lapangan->delete();

        return redirect()
            ->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil dihapus.');
    }
}