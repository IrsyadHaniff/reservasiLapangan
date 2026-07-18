<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminLapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::latest()->get();

        return view('admin.lapangan.index', compact('lapangans'));
    }

    public function create()
    {
        return view('admin.lapangan.form', ['lapangan' => new Lapangan()]);
    }

    public function store(Request $request)
    {
        $validated = $this->baseValidate($request);
        $validated['is_aktif'] = $request->boolean('is_aktif');

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('lapangan', 'public');
        }

        Lapangan::create($validated);

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(Lapangan $lapangan)
    {
        return view('admin.lapangan.form', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $validated = $this->baseValidate($request);
        $validated['is_aktif'] = $request->boolean('is_aktif');

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama kalau ada dan memang file upload (bukan path dummy assets/img/...)
            if ($lapangan->gambar && Storage::disk('public')->exists($lapangan->gambar)) {
                Storage::disk('public')->delete($lapangan->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('lapangan', 'public');
        }

        $lapangan->update($validated);

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroy(Lapangan $lapangan)
    {
        if ($lapangan->reservasis()->exists()) {
            return back()->with('error', 'Lapangan ini punya histori reservasi, gak bisa dihapus. Nonaktifkan aja lewat tombol toggle.');
        }

        if ($lapangan->gambar && Storage::disk('public')->exists($lapangan->gambar)) {
            Storage::disk('public')->delete($lapangan->gambar);
        }

        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')->with('success', 'Lapangan berhasil dihapus.');
    }

    public function toggleAktif(Lapangan $lapangan)
    {
        $lapangan->update(['is_aktif' => ! $lapangan->is_aktif]);

        return back()->with('success', 'Status lapangan diperbarui.');
    }

    private function baseValidate(Request $request): array
    {
        return $request->validate([
            'nama'          => ['required', 'string', 'max:255'],
            'kategori'      => ['required', 'in:Futsal,Badminton'],
            'harga_per_jam' => ['required', 'integer', 'min:0'],
            'gambar'        => ['nullable', 'image', 'max:2048'],
            'rating'        => ['nullable', 'numeric', 'min:0', 'max:5'],
        ]);
    }
}