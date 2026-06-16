<?php

namespace App\Http\Controllers\Perpustakaan\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perpustakaan\PerpusPeminjaman;
use App\Models\Perpustakaan\PerpusPengembalian;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = PerpusPengembalian::with(['peminjaman.user', 'peminjaman.buku']);

        if ($request->filled('dari')) {
            $query->whereDate('tanggal_kembali', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_kembali', '<=', $request->sampai);
        }

        $pengembalians = $query->latest()->paginate(15)->withQueryString();

        // Total denda dalam periode
        $totalDenda = $query->sum('denda');

        // Statistik ringkas
        $totalPeminjaman   = PerpusPeminjaman::count();
        $totalDikembalikan = PerpusPeminjaman::where('status', 'dikembalikan')->count();
        $totalAktif        = PerpusPeminjaman::whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])->count();
        $totalTerlambat    = PerpusPeminjaman::whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
            ->where('batas_kembali', '<', now()->toDateString())
            ->count();

        return view('perpustakaan.admin.laporan.index', compact(
            'pengembalians',
            'totalDenda',
            'totalPeminjaman',
            'totalDikembalikan',
            'totalAktif',
            'totalTerlambat'
        ));
    }

    public function export(Request $request)
    {
        $query = PerpusPeminjaman::with(['user', 'buku', 'pengembalian']);

        // Samakan filter dengan index (disini index pakai pengembalian, tapi user minta laporan peminjaman)
        if ($request->filled('dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai);
        }

        $data = $query->orderBy('tanggal_pinjam', 'asc')->get();

        $filename = "Laporan_Perpustakaan_" . date('Y-m-d') . ".xls";
        
        $headers = [
            "Content-Type"        => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Expires"             => "0"
        ];

        $callback = function() use($data) {
            $output = fopen('php://output', 'w');
            
            // Start HTML Table for Excel
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><style>table { border-collapse: collapse; } th { background-color: #f2f2f2; border: 1px solid #000; } td { border: 1px solid #000; }</style></head><body>';
            echo '<h3>LAPORAN PEMINJAMAN BUKU PERPUSTAKAAN</h3>';
            echo '<p>Tanggal Cetak: ' . date('d/m/Y H:i') . '</p>';
            echo '<table>';
            echo '<thead><tr>';
            echo '<th>ID</th><th>No Anggota</th><th>Nama Siswa</th><th>Judul Buku</th><th>Tgl Pinjam</th><th>Batas Kembali</th><th>Tgl Kembali</th><th>Status</th><th>Hari Terlambat</th><th>Denda</th>';
            echo '</tr></thead><tbody>';

            $totalDenda = 0;
            foreach ($data as $item) {
                $terlambatDays = $item->pengembalian ? $item->pengembalian->hari_terlambat : 0;
                $dendaVal = $item->pengembalian ? $item->pengembalian->denda : 0;
                $totalDenda += $dendaVal;

                echo '<tr>';
                echo '<td>' . $item->id . '</td>';
                echo '<td>' . ($item->user->no_anggota ?? '-') . '</td>';
                echo '<td>' . $item->user->name . '</td>';
                echo '<td>' . $item->buku->judul . '</td>';
                echo '<td>' . $item->tanggal_pinjam->format('d/m/Y') . '</td>';
                echo '<td>' . $item->batas_kembali->format('d/m/Y') . '</td>';
                echo '<td>' . ($item->pengembalian ? $item->pengembalian->tanggal_kembali->format('d/m/Y') : 'Belum Kembali') . '</td>';
                echo '<td>' . ucfirst($item->status) . '</td>';
                echo '<td>' . ($terlambatDays > 0 ? $terlambatDays . ' hari' : '0') . '</td>';
                echo '<td>' . ($dendaVal > 0 ? 'Rp ' . number_format($dendaVal, 0, ',', '.') : 'Rp 0') . '</td>';
                echo '</tr>';
            }

            // Tambahkan baris total denda di akhir tabel
            echo '<tr>';
            echo '<td colspan="9" style="text-align: right; font-weight: bold; border: 1px solid #000; padding: 6px; background-color: #f2f2f2;">Total Denda:</td>';
            echo '<td style="font-weight: bold; border: 1px solid #000; padding: 6px; background-color: #f9f9f9; color: #dc2626;">Rp ' . number_format($totalDenda, 0, ',', '.') . '</td>';
            echo '</tr>';
            
            echo '</tbody></table>';

            // Tambahan tempat tanda tangan
            echo '<br><br>';
            echo '<table style="border: none;">';
            echo '<tr>';
            echo '<td colspan="7" style="border: none;"></td>';
            echo '<td colspan="3" style="border: none; text-align: center;">Mengetahui,</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td colspan="7" style="border: none;"></td>';
            echo '<td colspan="3" style="border: none; text-align: center;">Admin Perpustakaan</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td colspan="7" style="border: none;"></td>';
            echo '<td colspan="3" style="border: none; height: 60px;"></td>'; // Space for signature
            echo '</tr>';
            echo '<tr>';
            echo '<td colspan="7" style="border: none;"></td>';
            echo '<td colspan="3" style="border: none; text-align: center;">(.........................................)</td>';
            echo '</tr>';
            echo '</table>';

            echo '</body></html>';
            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}
