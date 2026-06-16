<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Halaman checkout keranjang: tampilkan semua item di keranjang
     */
    public function create(Request $request)
    {
        $cart = session('cart_bengkel', []);
        
        // Handling struktur keranjang lama (array numerik) jika masih nyangkut
        if (!empty($cart) && array_keys($cart) === range(0, count($cart) - 1)) {
            $newCart = [];
            foreach ($cart as $id) {
                $newCart[$id] = 1;
            }
            $cart = $newCart;
            session(['cart_bengkel' => $cart]);
        }

        $cartItems = Barang::whereIn('id', array_keys($cart))->get();

        return view('siswa.peminjaman.create', compact('cartItems', 'cart'));
    }

    /**
     * LOGIKA KRITIS: Proses Batch Checkout Peminjaman
     * - Loop semua item di keranjang
     * - Gunakan DB Transaction + lockForUpdate untuk mencegah race condition
     * - Batas kembali = tanggal_pinjam + 5 hari (regulasi sekolah)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'catatan'        => 'nullable|string|max:300',
        ]);

        $cart = session('cart_bengkel', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong. Silakan tambahkan alat terlebih dahulu.');
        }

        $tanggalPinjam = $validated['tanggal_pinjam'];
        $batasKembali  = \Carbon\Carbon::parse($tanggalPinjam)->addDays(5)->toDateString();

        $berhasil = [];
        $gagal    = [];

        try {
            DB::transaction(function () use ($cart, $validated, $tanggalPinjam, $batasKembali, &$berhasil, &$gagal) {
                foreach ($cart as $barangId => $qty) {
                    // Lock baris barang untuk mencegah race condition
                    $barang = Barang::lockForUpdate()->find($barangId);

                    if (!$barang) {
                        $gagal[] = "Barang ID {$barangId} tidak ditemukan.";
                        continue;
                    }

                    // Validasi stok real-time
                    if ($barang->stok < $qty) {
                        $gagal[] = "Stok \"{$barang->nama_barang}\" tidak mencukupi untuk meminjam {$qty} unit. Sisa stok: {$barang->stok}.";
                        continue;
                    }

                    // Validasi kondisi barang
                    if ($barang->kondisi !== 'baik') {
                        $gagal[] = "\"{$barang->nama_barang}\" sedang dalam kondisi {$barang->kondisi}.";
                        continue;
                    }

                    // Kurangi stok barang sejumlah qty
                    $barang->decrement('stok', $qty);

                    // Buat 1 record peminjaman dengan jumlah sesuai qty
                    Peminjaman::create([
                        'user_id'        => auth()->id(),
                        'barang_id'      => $barang->id,
                        'jumlah'         => $qty,
                        'tanggal_pinjam' => $tanggalPinjam,
                        'batas_kembali'  => $batasKembali,
                        'status'         => 'dipinjam',
                        'catatan'        => $validated['catatan'] ?? null,
                    ]);

                    $berhasil[] = "{$barang->nama_barang} ({$qty} unit)";
                }
            });

            // Kosongkan keranjang setelah checkout
            session()->forget('cart_bengkel');

            if (!empty($gagal) && empty($berhasil)) {
                return back()->with('error', 'Semua peminjaman gagal: ' . implode(', ', $gagal));
            }

            $message = count($berhasil) . ' alat berhasil dipinjam: ' . implode(', ', $berhasil) . '. Batas kembali: ' . $batasKembali . '.';
            if (!empty($gagal)) {
                $message .= ' Gagal: ' . implode(', ', $gagal);
            }

            return redirect()->route('siswa.riwayat')->with('success', $message);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Riwayat peminjaman milik siswa yang sedang login
     */
    public function riwayat(Request $request)
    {
        $query = Peminjaman::with('barang')
            ->where('user_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjamans = $query->latest()->paginate(10)->withQueryString();

        return view('siswa.riwayat', compact('peminjamans'));
    }

    /**
     * Proses pengembalian barang oleh siswa
     */
    public function prosesKembali($id)
    {
        $peminjaman = Peminjaman::with('barang')->where('user_id', auth()->id())->findOrFail($id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Barang ini tidak sedang dipinjam.');
        }

        try {
            // Update status peminjaman menjadi menunggu_konfirmasi
            // Tidak ada perubahan stok atau tanggal_kembali di sini, karena dilakukan oleh admin setelah konfirmasi fisik
            $peminjaman->update([
                'status' => 'menunggu_konfirmasi',
            ]);

            return back()->with('success', "Pengembalian barang \"{$peminjaman->barang->nama_barang}\" telah diajukan. Silakan serahkan barang ke admin bengkel untuk dikonfirmasi.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }
}
