<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Peminjaman Buku Perpustakaan</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h3 { text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-danger { color: #dc2626; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 4px; }
        th { background-color: #f2f2f2; }
        .signature { width: 100%; margin-top: 40px; border: none; }
        .signature td { border: none; }
        .signature-box { height: 60px; }
    </style>
</head>
<body>
    <h3>LAPORAN PEMINJAMAN BUKU PERPUSTAKAAN</h3>
    <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>No Anggota</th>
                <th>Nama Siswa</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Batas Kembali</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Hari Terlambat</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @php $totalDenda = 0; @endphp
            @foreach($data as $item)
                @php
                    $terlambatDays = $item->pengembalian ? $item->pengembalian->hari_terlambat : 0;
                    $dendaVal = $item->pengembalian ? $item->pengembalian->denda : 0;
                    $totalDenda += $dendaVal;
                @endphp
            <tr>
                <td class="text-center">{{ $item->id }}</td>
                <td class="text-center">{{ $item->user->no_anggota ?? '-' }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->buku->judul }}</td>
                <td class="text-center">{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                <td class="text-center">{{ $item->batas_kembali->format('d/m/Y') }}</td>
                <td class="text-center">{{ $item->pengembalian ? $item->pengembalian->tanggal_kembali->format('d/m/Y') : 'Belum Kembali' }}</td>
                <td class="text-center">{{ ucfirst($item->status) }}</td>
                <td class="text-center">{{ $terlambatDays > 0 ? $terlambatDays . ' hari' : '0' }}</td>
                <td class="text-right">{{ $dendaVal > 0 ? 'Rp ' . number_format($dendaVal, 0, ',', '.') : 'Rp 0' }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="9" class="text-right font-bold" style="background-color: #f2f2f2;">Total Denda:</td>
                <td class="font-bold text-danger text-right" style="background-color: #f9f9f9;">Rp {{ number_format($totalDenda, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table class="signature">
        <tr>
            <td width="70%"></td>
            <td class="text-center">Mengetahui,</td>
        </tr>
        <tr>
            <td></td>
            <td class="text-center">Admin Perpustakaan</td>
        </tr>
        <tr>
            <td></td>
            <td class="signature-box"></td>
        </tr>
        <tr>
            <td></td>
            <td class="text-center">(.........................................)</td>
        </tr>
    </table>
</body>
</html>
