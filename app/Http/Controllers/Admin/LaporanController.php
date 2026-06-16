<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'barang']);

        // Filter berdasarkan tanggal pinjam
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->date_to);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter siswa
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $peminjamans = $query->orderBy('tanggal_pinjam', 'desc')->paginate(15)->withQueryString();

        // Summary untuk periode yang difilter
        $summary = [
            'total'        => $query->count(),
            'dipinjam'     => (clone $query)->where('status', 'dipinjam')->count(),
            'dikembalikan' => (clone $query)->where('status', 'dikembalikan')->count(),
        ];

        // Data siswa untuk dropdown filter
        $siswas = \App\Models\User::where('role', 'siswa')->orderBy('name')->get();

        return view('admin.laporan.index', compact('peminjamans', 'summary', 'siswas'));
    }

    public function export(Request $request)
    {
        $query = Peminjaman::with(['user', 'barang']);

        // Samakan filter dengan index
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $data = $query->orderBy('tanggal_pinjam', 'asc')->get();

        $filename = "Laporan_Peminjaman_Bengkel_" . date('Y-m-d') . ".xls";
        
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
            echo '<h3>LAPORAN PEMINJAMAN ALAT BENGKEL</h3>';
            echo '<p>Tanggal Cetak: ' . date('d/m/Y H:i') . '</p>';
            echo '<table>';
            echo '<thead><tr>';
            echo '<th>ID</th><th>Nama Siswa</th><th>Nama Alat</th><th>Kode Alat</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th>';
            echo '</tr></thead><tbody>';

            foreach ($data as $item) {
                echo '<tr>';
                echo '<td>' . $item->id . '</td>';
                echo '<td>' . $item->user->name . '</td>';
                echo '<td>' . $item->barang->nama_barang . '</td>';
                echo '<td>' . $item->barang->kode_barang . '</td>';
                echo '<td>' . $item->tanggal_pinjam->format('d/m/Y') . '</td>';
                echo '<td>' . ($item->tanggal_kembali ? $item->tanggal_kembali->format('d/m/Y') : 'Belum Kembali') . '</td>';
                echo '<td>' . ucfirst($item->status) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
            
            // Tambahan tempat tanda tangan
            echo '<br><br>';
            echo '<table style="border: none;">';
            echo '<tr>';
            echo '<td colspan="5" style="border: none;"></td>';
            echo '<td colspan="2" style="border: none; text-align: center;">Mengetahui,</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td colspan="5" style="border: none;"></td>';
            echo '<td colspan="2" style="border: none; text-align: center;">Admin Bengkel</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td colspan="5" style="border: none;"></td>';
            echo '<td colspan="2" style="border: none; height: 60px;"></td>'; // Space for signature
            echo '</tr>';
            echo '<tr>';
            echo '<td colspan="5" style="border: none;"></td>';
            echo '<td colspan="2" style="border: none; text-align: center;">(.........................................)</td>';
            echo '</tr>';
            echo '</table>';

            echo '</body></html>';
            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}
