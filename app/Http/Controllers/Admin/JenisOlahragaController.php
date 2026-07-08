<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisOlahraga;
use Illuminate\Http\Request;

class JenisOlahragaController extends Controller
{
    public function index()
    {
        $jenisOlahragas = JenisOlahraga::withCount('lapangan')
            ->latest()
            ->get();

        return view('admin.jenis-olahraga.index', compact('jenisOlahragas'));
    }

    public function create()
    {
        return view('admin.jenis-olahraga.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $sudahAda = JenisOlahraga::whereRaw('LOWER(name) = ?', [strtolower($data['name'])])->exists();

        if ($sudahAda) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Jenis olahraga dengan nama ini sudah ada.',
                    'errors' => [
                        'name' => ['Jenis olahraga dengan nama ini sudah ada.']
                    ],
                ], 422);
            }

            return redirect()->back()
                ->withErrors([
                    'name' => 'Jenis olahraga dengan nama ini sudah ada.'
                ])
                ->withInput();
        }

        $jenisOlahraga = JenisOlahraga::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $jenisOlahraga->id,
                'name' => $jenisOlahraga->name,
            ], 201);
        }

        return redirect()->back()->with('success', 'Jenis olahraga berhasil ditambahkan.');
    }

    public function edit(JenisOlahraga $jenisOlahraga)
    {
        return view('admin.jenis-olahraga.edit', compact('jenisOlahraga'));
    }

    public function update(Request $request, JenisOlahraga $jenisOlahraga)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $jenisOlahraga->update($data);

        return redirect()
            ->route('admin.jenis-olahraga.index')
            ->with('success', 'Jenis olahraga berhasil diperbarui.');
    }

    public function destroy(JenisOlahraga $jenisOlahraga)
    {
        $jenisOlahraga->delete();

        return redirect()
            ->route('admin.jenis-olahraga.index')
            ->with('success', 'Jenis olahraga berhasil dihapus.');
    }
}
