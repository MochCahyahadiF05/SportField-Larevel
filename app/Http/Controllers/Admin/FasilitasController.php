<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fasilitas;
use Illuminate\Http\Request;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::withCount('lapangan')
            ->latest()
            ->get();

        return view('admin.fasilitas.index', compact('fasilitas'));
    }

    public function create()
    {
        return view('admin.fasilitas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $sudahAda = Fasilitas::whereRaw('LOWER(name) = ?', [strtolower($data['name'])])->exists();

        if ($sudahAda) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Fasilitas dengan nama ini sudah ada.',
                    'errors' => [
                        'name' => ['Fasilitas dengan nama ini sudah ada.']
                    ],
                ], 422);
            }

            return redirect()->back()
                ->withErrors([
                    'name' => 'Fasilitas dengan nama ini sudah ada.'
                ])
                ->withInput();
        }

        $fasilitas = Fasilitas::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $fasilitas->id,
                'name' => $fasilitas->name,
            ], 201);
        }

        return redirect()->back()->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit(Fasilitas $fasilitas)
    {
        return view('admin.fasilitas.edit', compact('fasilitas'));
    }

    public function update(Request $request, Fasilitas $fasilitas)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $fasilitas->update($data);

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Fasilitas $fasilitas)
    {
        $fasilitas->delete();

        return redirect()
            ->route('admin.fasilitas.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }
}
