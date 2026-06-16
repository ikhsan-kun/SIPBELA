<?php

namespace App\Http\Controllers\Perpustakaan\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan\PerpusBuku;
use App\Models\Perpustakaan\PerpusPeminjaman;
use App\Models\Perpustakaan\PerpusUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
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

    /**
     * Halaman checkout keranjang buku perpustakaan
     */
    public function create()
    {
        $user = Auth::user();
        $perpusUser = $this->getOrCreatePerpusUser($user);
        
        $cart = session('cart_perpus', []);
        
        // Handling struktur keranjang lama (array numerik) jika masih nyangkut
        if (!empty($cart) && array_keys($cart) === range(0, count($cart) - 1)) {
            $newCart = [];
            foreach ($cart as $id) {
                $newCart[$id] = 1;
            }
            $cart = $newCart;
            session(['cart_perpus' => $cart]);
        }

        $cartItems = PerpusBuku::whereIn('id', array_keys($cart))->get();

        return view('perpustakaan.siswa.peminjaman.create', compact('cartItems', 'cart'));
    }

    /**
     * Proses Batch Checkout Peminjaman Buku
     * - Loop semua buku di keranjang
     * - Gunakan DB transaction dan lockForUpdate
     * - Batas kembali = tanggal_pinjam + 7 hari (regulasi perpus)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'catatan'        => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $perpusUser = $this->getOrCreatePerpusUser($user);

        $cart = session('cart_perpus', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong. Silakan tambahkan buku terlebih dahulu.');
        }

        $tanggalPinjam = $validated['tanggal_pinjam'];
        $batasKembali  = \Carbon\Carbon::parse($tanggalPinjam)->addDays(7)->toDateString();

        $berhasil = [];
        $gagal    = [];

        try {
            DB::transaction(function () use ($cart, $validated, $tanggalPinjam, $batasKembali, $perpusUser, &$berhasil, &$gagal) {
                foreach ($cart as $bukuId => $qty) {
                    // Lock baris buku untuk mencegah race condition
                    $buku = PerpusBuku::lockForUpdate()->find($bukuId);

                    if (!$buku) {
                        $gagal[] = "Buku ID {$bukuId} tidak ditemukan.";
                        continue;
                    }

                    // Validasi stok
                    if ($buku->stok < $qty) {
                        $gagal[] = "Stok buku \"{$buku->judul}\" tidak mencukupi untuk meminjam {$qty} eksemplar. Sisa stok: {$buku->stok}.";
                        continue;
                    }

                    // Cek duplikasi peminjaman aktif
                    $sudahPinjam = PerpusPeminjaman::where('user_id', $perpusUser->id)
                        ->where('buku_id', $buku->id)
                        ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
                        ->exists();

                    if ($sudahPinjam) {
                        $gagal[] = "Anda sudah meminjam buku \"{$buku->judul}\" dan belum dikembalikan.";
                        continue;
                    }

                    // Kurangi stok buku sejumlah qty
                    $buku->decrement('stok', $qty);

                    // Buat 1 record peminjaman dengan jumlah
                    PerpusPeminjaman::create([
                        'user_id'        => $perpusUser->id,
                        'buku_id'        => $buku->id,
                        'jumlah'         => $qty,
                        'tanggal_pinjam' => $tanggalPinjam,
                        'batas_kembali'  => $batasKembali,
                        'status'         => 'dipinjam',
                        'catatan'        => $validated['catatan'] ?? null,
                    ]);

                    $berhasil[] = "{$buku->judul} ({$qty} eks)";
                }
            });

            // Kosongkan keranjang setelah checkout
            session()->forget('cart_perpus');

            if (!empty($gagal) && empty($berhasil)) {
                return back()->with('error', 'Semua peminjaman gagal: ' . implode(', ', $gagal));
            }

            $message = count($berhasil) . ' buku berhasil dipinjam. Batas pengembalian: ' . \Carbon\Carbon::parse($batasKembali)->format('d M Y') . '.';
            if (!empty($gagal)) {
                $message .= ' Gagal: ' . implode(', ', $gagal);
            }

            return redirect()->route('perpustakaan.siswa.dashboard')->with('success', $message);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function riwayat()
    {
        $user = Auth::user();
        $perpusUser = $this->getOrCreatePerpusUser($user);

        $peminjamans = PerpusPeminjaman::with('buku', 'pengembalian')
            ->where('user_id', $perpusUser->id)
            ->latest()
            ->paginate(10);

        return view('perpustakaan.siswa.riwayat.index', compact('peminjamans'));
    }

    public function prosesKembali($id)
    {
        $user = Auth::user();
        $perpusUser = $this->getOrCreatePerpusUser($user);

        $peminjaman = PerpusPeminjaman::with('buku')
            ->where('user_id', $perpusUser->id)
            ->findOrFail($id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku ini tidak sedang dipinjam.');
        }

        try {
            $peminjaman->update([
                'status' => 'menunggu_konfirmasi',
            ]);

            return back()->with('success', "Pengembalian buku \"{$peminjaman->buku->judul}\" telah diajukan. Silakan serahkan fisik buku ke perpustakaan.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }

    public function perpanjang($id)
    {
        $user = Auth::user();
        $perpusUser = $this->getOrCreatePerpusUser($user);

        $peminjaman = PerpusPeminjaman::with('buku')
            ->where('user_id', $perpusUser->id)
            ->findOrFail($id);

        if (!$peminjaman->bisaDiperpanjang()) {
            return back()->with('error', 'Buku ini sudah tidak bisa diperpanjang (hanya bisa 1 kali perpanjangan).');
        }

        try {
            // Tambahkan 7 hari ke batas kembali
            $batasBaru = \Carbon\Carbon::parse($peminjaman->batas_kembali)->addDays(7)->toDateString();

            $peminjaman->update([
                'batas_kembali' => $batasBaru,
                'jumlah_perpanjangan' => $peminjaman->jumlah_perpanjangan + 1,
            ]);

            return back()->with('success', "Peminjaman buku \"{$peminjaman->buku->judul}\" berhasil diperpanjang hingga " . \Carbon\Carbon::parse($batasBaru)->format('d M Y') . ".");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperpanjang peminjaman: ' . $e->getMessage());
        }
    }
}
