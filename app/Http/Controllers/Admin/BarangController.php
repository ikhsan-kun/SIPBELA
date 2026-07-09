<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $barangs = $query->latest()->paginate(10)->withQueryString();

        return view('admin.barangs.index', compact('barangs'));
    }

    public function create()
    {
        return view('admin.barangs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang'     => 'required|string|max:20|unique:barangs,kode_barang',
            'nama_barang'     => 'required|string|max:100',
            'stok'            => 'required|integer|min:0',
            'kondisi'         => 'required|in:baik,rusak,diperbaiki',
            'batas_pemakaian' => 'required|integer|min:0',
            'deskripsi'       => 'nullable|string|max:500',
        ]);

        Barang::create($validated);

        return redirect()->route('admin.barangs.index')
            ->with('success', "Barang \"{$validated['nama_barang']}\" berhasil ditambahkan.");
    }

    public function edit(Barang $barang)
    {
        return view('admin.barangs.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode_barang'     => "required|string|max:20|unique:barangs,kode_barang,{$barang->id}",
            'nama_barang'     => 'required|string|max:100',
            'stok'            => 'required|integer|min:0',
            'kondisi'         => 'required|in:baik,rusak,diperbaiki',
            'batas_pemakaian' => 'required|integer|min:0',
            'deskripsi'       => 'nullable|string|max:500',
        ]);

        $barang->update($validated);

        return redirect()->route('admin.barangs.index')
            ->with('success', "Barang \"{$barang->nama_barang}\" berhasil diperbarui.");
    }

    public function destroy(Barang $barang)
    {
        // Cek apakah barang sedang dipinjam
        if ($barang->peminjamanAktif()->exists()) {
            return back()->with('error', "Barang \"{$barang->nama_barang}\" tidak dapat dihapus karena sedang dipinjam.");
        }

        $nama = $barang->nama_barang;
        $barang->delete();

        return redirect()->route('admin.barangs.index')
            ->with('success', "Barang \"{$nama}\" berhasil dihapus.");
    }

    public function resetMaintenance(Barang $barang)
    {
        $barang->update([
            'jumlah_dipakai' => 0,
            'kondisi' => 'baik'
        ]);

        return back()->with('success', "Siklus pemakaian barang \"{$barang->nama_barang}\" berhasil di-reset menjadi 0 dan kondisinya kembali 'Baik'. Alat siap digunakan.");
    }
}
