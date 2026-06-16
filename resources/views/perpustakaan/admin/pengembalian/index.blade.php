@extends('perpustakaan.layouts.app')

@section('title', 'Proses Pengembalian')
@section('page-title', 'Pengembalian Buku')
@section('page-subtitle', 'Proses pengembalian buku dan hitung denda keterlambatan')

@section('content')
<!-- Keterangan denda -->
<div class="flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl text-sm mb-6">
    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
    <div>
        <strong>Aturan Denda:</strong> Batas peminjaman 7 hari. Keterlambatan dikenakan denda <strong>Rp 1.000 per hari</strong>.
        Denda dihitung otomatis berdasarkan tanggal kembali yang diinput.
    </div>
</div>

<!-- Filter -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama anggota atau judul buku..."
            class="flex-1 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <button type="submit" class="btn-perpus">Cari</button>
    </form>
</div>

<!-- Table -->
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="table-th">No</th>
                    <th class="table-th">Anggota</th>
                    <th class="table-th">Buku</th>
                    <th class="table-th text-center">Jml</th>
                    <th class="table-th">Tgl Pinjam</th>
                    <th class="table-th">Batas Kembali</th>
                    <th class="table-th">Status</th>
                    <th class="table-th text-center">Proses Kembali</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($peminjamans as $i => $p)
                <tr class="hover:bg-slate-50 transition-colors {{ $p->isTerlambat() ? 'bg-red-50/40' : '' }}">
                    <td class="table-td text-slate-400 text-xs">{{ $peminjamans->firstItem() + $i }}</td>
                    <td class="table-td">
                        <div class="font-semibold text-slate-800">{{ $p->user->name }}</div>
                        <div class="text-xs text-slate-400">{{ $p->user->kelas ?? '-' }}</div>
                    </td>
                    <td class="table-td">
                        <div class="font-medium text-slate-700">{{ Str::limit($p->buku->judul, 28) }}</div>
                        <div class="text-xs text-slate-400">{{ $p->buku->penulis }}</div>
                    </td>
                    <td class="table-td text-center font-bold text-slate-700">{{ $p->jumlah ?? 1 }}</td>
                    <td class="table-td text-slate-600">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="table-td">
                        <span class="{{ $p->isTerlambat() ? 'text-red-600 font-bold' : 'text-slate-600' }}">
                            {{ $p->batas_kembali->format('d/m/Y') }}
                        </span>
                        @if($p->isTerlambat())
                        <div class="text-xs text-red-500 font-semibold">
                            ⚠ Terlambat {{ $p->hariTerlambatSekarang() }} hari
                            (≈ Rp {{ number_format($p->hariTerlambatSekarang() * 1000, 0, ',', '.') }})
                        </div>
                        @endif
                    </td>
                    <td class="table-td">
                        @if($p->status === 'menunggu_konfirmasi')
                            <span class="badge-menunggu">⏳ Menunggu Konfirmasi</span>
                        @elseif($p->isTerlambat())
                            <span class="badge-terlambat">⚠ Terlambat</span>
                        @else
                            <span class="badge-dipinjam">Dipinjam</span>
                        @endif
                    </td>
                    <td class="table-td">
                        <!-- Form proses pengembalian -->
                        <form action="{{ route('perpustakaan.admin.pengembalian.proses', $p->id) }}" method="POST"
                               class="flex flex-col gap-2">
                            @csrf
                            <button type="button"
                                class="btn-perpus text-xs py-1.5 justify-center"
                                onclick="confirmKembaliPerpus(this.form, '{{ addslashes($p->buku->judul) }}', '{{ addslashes($p->user->name) }}', {{ $p->isTerlambat() ? 'true' : 'false' }}, {{ $p->hariTerlambatSekarang() }}, '{{ $p->status }}', {{ $p->jumlah ?? 1 }})">
                                {{ $p->status === 'menunggu_konfirmasi' ? '✓ Konfirmasi' : '✓ Kembalikan' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="table-td text-center text-slate-400 py-10">
                        <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Tidak ada buku yang perlu dikembalikan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($peminjamans->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">{{ $peminjamans->links() }}</div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmKembaliPerpus(form, judulBuku, namaSiswa, isTerlambat, hariTerlambat, status, jumlah) {
    let confirmText = status === 'menunggu_konfirmasi' ? 'Ya, Konfirmasi Kembali!' : 'Ya, Kembalikan Buku!';
    let actionText = status === 'menunggu_konfirmasi' ? 'mengonfirmasi pengembalian' : 'memproses pengembalian';
    let htmlContent = `Apakah Anda yakin ingin ${actionText} <strong class="text-green-700">${jumlah} unit "${judulBuku}"</strong> oleh <strong>${namaSiswa}</strong>?`;
    let icon = 'question';

    if (isTerlambat) {
        const denda = hariTerlambat * 1000;
        const dendaFmt = new Intl.NumberFormat('id-ID').format(denda);
        icon = 'warning';
        htmlContent += `<br><br><div class="p-3 bg-red-50 border border-red-200 text-red-800 rounded-xl text-xs text-left" style="text-align: left;">
            <p class="font-bold">⚠️ Buku Terlambat ${hariTerlambat} Hari!</p>
            <p class="mt-1">Denda Keterlambatan: <strong class="text-red-600 text-sm">Rp ${dendaFmt}</strong></p>
            <p class="mt-1 text-[10px] text-slate-500">Pastikan siswa telah membayar denda kepada petugas sebelum menyetujui.</p>
        </div>`;
    }

    Swal.fire({
        title: isTerlambat ? 'Buku Terlambat!' : 'Konfirmasi Pengembalian',
        html: htmlContent,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: isTerlambat ? '#ef4444' : '#15803d', // red-500 vs green-700
        cancelButtonColor: '#64748b', // slate-500
        confirmButtonText: confirmText,
        cancelButtonText: 'Batal',
        background: '#ffffff',
        customClass: {
            popup: 'rounded-2xl',
        }
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>
@endsection
