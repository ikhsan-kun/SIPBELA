<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Peminjaman Alat Bengkel</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h3 { text-align: center; }
        .text-center { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #f2f2f2; }
        .signature { width: 100%; margin-top: 50px; border: none; }
        .signature td { border: none; }
        .signature-box { height: 70px; }
    </style>
</head>
<body>
    <h3>LAPORAN PEMINJAMAN ALAT BENGKEL</h3>
    <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Siswa</th>
                <th>Nama Alat</th>
                <th>Kode Alat</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td class="text-center">{{ $item->id }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->barang->nama_barang }}</td>
                <td class="text-center">{{ $item->barang->kode_barang }}</td>
                <td class="text-center">{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                <td class="text-center">{{ $item->tanggal_kembali ? $item->tanggal_kembali->format('d/m/Y') : 'Belum Kembali' }}</td>
                <td class="text-center">{{ ucfirst($item->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signature">
        <tr>
            <td width="70%"></td>
            <td class="text-center">Mengetahui,</td>
        </tr>
        <tr>
            <td></td>
            <td class="text-center">Admin Bengkel</td>
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
