<?php

namespace App\Http\Controllers\Perpustakaan\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan\PerpusBuku;
use App\Models\Perpustakaan\PerpusPeminjaman;
use App\Models\Perpustakaan\PerpusUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private function getOrCreatePerpusUser($user)
    {
        $perpusUser = PerpusUser::where('nis', $user->nis)->first();
        if (!$perpusUser) {
            $noAnggota = 'P-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            while (PerpusUser::where('no_anggota', $noAnggota)->exists()) {
                $noAnggota = 'P-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }

            $perpusUser = PerpusUser::create([
                'name'       => $user->name,
                'nis'        => $user->nis,
                'username'   => $user->username,
                'email'      => $user->email,
                'password'   => $user->password,
                'role'       => 'siswa',
                'no_anggota' => $noAnggota,
                'kelas'      => $user->kelas,
                'jurusan'    => $user->jurusan,
            ]);
        }
        return $perpusUser;
    }

    public function index()
    {
        $user = Auth::user();
        $perpusUser = $this->getOrCreatePerpusUser($user);

        $totalPinjam  = PerpusPeminjaman::where('user_id', $perpusUser->id)->count();
        $aktif        = PerpusPeminjaman::where('user_id', $perpusUser->id)->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->count();
        $totalBuku    = PerpusBuku::count();

        // Cek apakah ada yang terlambat
        $terlambat = PerpusPeminjaman::where('user_id', $perpusUser->id)
            ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
            ->where('batas_kembali', '<', now()->toDateString())
            ->count();

        // Peminjaman aktif user
        $peminjamanAktif = PerpusPeminjaman::with(['buku', 'pengembalian'])
            ->where('user_id', $perpusUser->id)
            ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
            ->latest()
            ->get();

        // Buku tersedia terbaru
        $bukuTersedia = PerpusBuku::where('stok', '>', 0)->latest()->take(6)->get();

        // Ambil data keranjang perpustakaan dari session
        $cart = session('cart_perpus', []);

        return view('perpustakaan.siswa.dashboard', compact(
            'user',
            'totalPinjam',
            'aktif',
            'totalBuku',
            'terlambat',
            'peminjamanAktif',
            'bukuTersedia',
            'cart'
        ));
    }

    /**
     * API endpoint: data dashboard real-time (JSON)
     */
    public function apiData()
    {
        $user = Auth::user();
        $perpusUser = $this->getOrCreatePerpusUser($user);

        $totalPinjam  = PerpusPeminjaman::where('user_id', $perpusUser->id)->count();
        $aktif        = PerpusPeminjaman::where('user_id', $perpusUser->id)->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->count();
        $totalBuku    = PerpusBuku::count();
        $terlambat    = PerpusPeminjaman::where('user_id', $perpusUser->id)
            ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
            ->where('batas_kembali', '<', now()->toDateString())
            ->count();

        $bukuTersedia = PerpusBuku::where('stok', '>', 0)->latest()->take(6)->get();

        $cart = session('cart_perpus', []);

        return response()->json([
            'totalPinjam'  => $totalPinjam,
            'aktif'        => $aktif,
            'totalBuku'    => $totalBuku,
            'terlambat'    => $terlambat,
            'bukuTersedia' => $bukuTersedia,
            'cart'         => $cart,
            'cart_count'   => array_sum($cart),
        ]);
    }

    /**
     * Tambah buku ke keranjang (session)
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:perpus_bukus,id',
        ]);

        $buku = PerpusBuku::findOrFail($request->buku_id);

        // Cek ketersediaan
        if ($buku->stok <= 0) {
            return response()->json(['success' => false, 'message' => 'Buku tidak tersedia (stok habis).'], 422);
        }

        $cart = session('cart_perpus', []);
        
        // Reset keranjang lama jika formatnya array numerik
        if (!empty($cart) && array_keys($cart) === range(0, count($cart) - 1)) {
            $newCart = [];
            foreach ($cart as $id) {
                $newCart[$id] = 1;
            }
            $cart = $newCart;
        }

        if (isset($cart[$buku->id])) {
            if ($cart[$buku->id] >= $buku->stok) {
                return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi untuk menambah lagi.'], 422);
            }
            $cart[$buku->id]++;
        } else {
            $cart[$buku->id] = 1;
        }

        session(['cart_perpus' => $cart]);

        return response()->json([
            'success'    => true,
            'message'    => "\"{$buku->judul}\" ditambahkan ke keranjang.",
            'cart_count' => array_sum($cart),
        ]);
    }

    /**
     * Hapus buku dari keranjang
     */
    public function removeFromCart($bukuId)
    {
        $cart = session('cart_perpus', []);
        if (isset($cart[$bukuId])) {
            unset($cart[$bukuId]);
        }
        session(['cart_perpus' => $cart]);

        return response()->json([
            'success'    => true,
            'message'    => 'Buku dihapus dari keranjang.',
            'cart_count' => array_sum($cart),
        ]);
    }

    /**
     * Update quantity buku di keranjang
     */
    public function updateCart(Request $request, $bukuId)
    {
        $request->validate([
            'qty' => 'required|integer|min:1'
        ]);

        $buku = PerpusBuku::findOrFail($bukuId);
        $cart = session('cart_perpus', []);

        if ($request->qty > $buku->stok) {
            return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi.'], 422);
        }

        if (isset($cart[$bukuId])) {
            $cart[$bukuId] = (int) $request->qty;
            session(['cart_perpus' => $cart]);
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
        $cart = session('cart_perpus', []);
        $bukus = PerpusBuku::whereIn('id', array_keys($cart))->get();

        return response()->json([
            'cart'       => $cart,
            'cart_count' => array_sum($cart),
            'items'      => $bukus,
        ]);
    }
}
