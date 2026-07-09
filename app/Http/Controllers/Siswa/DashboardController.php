<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Materi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua barang untuk katalog — real-time stok
        $barangs = Barang::orderBy('kondisi')
            ->orderByDesc('stok')
            ->get();

        // Statistik peminjaman siswa yang sedang login
        $user = auth()->user();
        $stats = [
            'sedang_dipinjam' => $user->peminjamans()->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->count(),
            'total_pinjam'    => $user->peminjamans()->count(),
        ];

        $materis = Materi::latest()->get();

        // Ambil data keranjang dari session
        $cart = session('cart_bengkel', []);

        return view('siswa.dashboard', compact('barangs', 'stats', 'materis', 'cart'));
    }

    /**
     * API endpoint: data katalog real-time (JSON)
     */
    public function apiData()
    {
        $barangs = Barang::orderBy('kondisi')->orderByDesc('stok')->get();

        $user = auth()->user();
        $stats = [
            'sedang_dipinjam' => $user->peminjamans()->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->count(),
            'total_pinjam'    => $user->peminjamans()->count(),
        ];

        $cart = session('cart_bengkel', []);
        $cartCount = array_sum($cart); // Jumlah total barang di keranjang

        return response()->json([
            'barangs'    => $barangs,
            'stats'      => $stats,
            'cart'       => $cart, // ini sekarang array [ id => qty ]
            'cart_count' => $cartCount,
        ]);
    }

    /**
     * Tambah barang ke keranjang (session)
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
        ]);

        $barang = Barang::findOrFail($request->barang_id);

        // Cek ketersediaan
        if ($barang->stok <= 0 || $barang->kondisi !== 'baik') {
            return response()->json(['success' => false, 'message' => 'Barang tidak tersedia.'], 422);
        }

        if ($barang->butuhMaintenance()) {
            return response()->json(['success' => false, 'message' => 'Barang tidak dapat dipinjam karena sedang menunggu jadwal servis.'], 422);
        }

        $cart = session('cart_bengkel', []);
        
        // Reset keranjang lama jika formatnya array numerik
        if (!empty($cart) && array_keys($cart) === range(0, count($cart) - 1)) {
            $newCart = [];
            foreach ($cart as $id) {
                $newCart[$id] = 1;
            }
            $cart = $newCart;
        }

        if (isset($cart[$barang->id])) {
            if ($cart[$barang->id] >= $barang->stok) {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi untuk menambah lagi.'], 422);
            }
            $cart[$barang->id]++;
        } else {
            $cart[$barang->id] = 1;
        }

        session(['cart_bengkel' => $cart]);

        return response()->json([
            'success'    => true,
            'message'    => "\"{$barang->nama_barang}\" ditambahkan ke keranjang.",
            'cart_count' => array_sum($cart),
        ]);
    }

    /**
     * Hapus barang dari keranjang
     */
    public function removeFromCart($barangId)
    {
        $cart = session('cart_bengkel', []);
        if (isset($cart[$barangId])) {
            unset($cart[$barangId]);
        }
        session(['cart_bengkel' => $cart]);

        return response()->json([
            'success'    => true,
            'message'    => 'Barang dihapus dari keranjang.',
            'cart_count' => array_sum($cart),
        ]);
    }

    /**
     * Update quantity barang di keranjang
     */
    public function updateCart(Request $request, $barangId)
    {
        $request->validate([
            'qty' => 'required|integer|min:1'
        ]);

        $barang = Barang::findOrFail($barangId);
        $cart = session('cart_bengkel', []);

        if ($request->qty > $barang->stok) {
            return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi.'], 422);
        }

        if (isset($cart[$barangId])) {
            $cart[$barangId] = (int) $request->qty;
            session(['cart_bengkel' => $cart]);
        }

        return response()->json([
            'success'    => true,
            'cart_count' => array_sum($cart),
        ]);
    }

    /**
     * Ambil data keranjang (JSON)
     */
    public function getCart()
    {
        $cart = session('cart_bengkel', []);
        $barangs = Barang::whereIn('id', array_keys($cart))->get();

        return response()->json([
            'cart'       => $cart,
            'cart_count' => array_sum($cart),
            'items'      => $barangs,
        ]);
    }
}
