@extends('perpustakaan.layouts.app')

@section('title', 'Data Peminjaman')
@section('page-title', 'Data Peminjaman Buku')
@section('page-subtitle', 'Semua transaksi peminjaman buku perpustakaan')

@section('content')
<!-- Filter -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama anggota atau judul buku..."
            class="flex-1 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <select name="status" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Semua Status</option>
            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
            <option value="menunggu_persetujuan" {{ request('status') == 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
            <option value="menunggu_konfirmasi" {{ request('status') == 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi Kembali</option>
            <option value="menunggu_perpanjangan" {{ request('status') == 'menunggu_perpanjangan' ? 'selected' : '' }}>Menunggu Perpanjangan</option>
            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <label class="flex items-center gap-2 text-sm text-slate-600 border border-slate-200 rounded-xl px-4 py-2.5 cursor-pointer hover:bg-slate-50">
            <input type="checkbox" name="terlambat" value="1" {{ request('terlambat') ? 'checked' : '' }}
                class="w-4 h-4 text-red-500 rounded">
            Hanya terlambat
        </label>
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
                    <th class="table-th">Denda</th>
                    <th class="table-th text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($peminjamans as $i => $p)
                <tr class="hover:bg-slate-50 transition-colors {{ $p->isTerlambat() ? 'bg-red-50/30' : '' }}">
                    <td class="table-td text-slate-400 text-xs">{{ $peminjamans->firstItem() + $i }}</td>
                    <td class="table-td">
                        <div class="font-semibold text-slate-800">{{ $p->user->name }}</div>
                        <div class="text-xs text-slate-400">{{ $p->user->kelas ?? '-' }} · {{ $p->user->no_anggota ?? '-' }}</div>
                    </td>
                    <td class="table-td">
                        <div class="font-medium text-slate-700">{{ Str::limit($p->buku->judul, 28) }}</div>
                        <div class="text-xs text-slate-400">{{ $p->buku->penulis }}</div>
                    </td>
                    <td class="table-td text-center font-bold text-slate-700">{{ $p->jumlah ?? 1 }}</td>
                    <td class="table-td text-slate-600 text-sm">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="table-td">
                        <span class="{{ $p->isTerlambat() ? 'text-red-600 font-bold' : 'text-slate-600' }} text-sm">
                            {{ $p->batas_kembali->format('d/m/Y') }}
                        </span>
                        @if($p->isTerlambat())
                            <div class="text-xs text-red-500">+{{ $p->hariTerlambatSekarang() }} hari</div>
                        @endif
                    </td>
                    <td class="table-td">
                        @if($p->status === 'dikembalikan')
                            <span class="badge-dikembalikan">✓ Dikembalikan</span>
                        @elseif($p->status === 'menunggu_persetujuan')
                            <span class="badge-menunggu !bg-yellow-100 !text-yellow-700">⌛ Menunggu Persetujuan</span>
                        @elseif($p->status === 'menunggu_konfirmasi')
                            <span class="badge-menunggu">⏳ Konfirmasi Kembali</span>
                        @elseif($p->status === 'menunggu_perpanjangan')
                            <span class="badge-menunggu">⏳ Konfirmasi Perpanjangan</span>
                        @elseif($p->status === 'ditolak')
                            <span class="badge-terlambat !bg-red-100 !text-red-700">❌ Ditolak</span>
                        @elseif($p->isTerlambat())
                            <span class="badge-terlambat">⚠ Terlambat</span>
                        @else
                            <span class="badge-dipinjam">Dipinjam</span>
                        @endif
                    </td>
                    <td class="table-td">
                        @if($p->pengembalian)
                            <span class="{{ $p->pengembalian->denda > 0 ? 'text-red-600 font-semibold' : 'text-green-600' }} text-sm">
                                {{ $p->pengembalian->dendaFormatted() }}
                            </span>
                        @elseif($p->isTerlambat())
                            <span class="text-orange-500 text-xs font-medium">
                                ~Rp {{ number_format($p->hariTerlambatSekarang() * 1000, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-slate-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="table-td text-center">
                        @if($p->status === 'menunggu_persetujuan')
                        <div class="flex items-center justify-center gap-2">
                            <form method="POST" action="{{ route('perpustakaan.admin.peminjaman.setujui', $p->id) }}" id="form-setujui-{{ $p->id }}">
                                @csrf
                                <button type="button" class="text-xs bg-green-600 text-white hover:bg-green-700 font-semibold px-3 py-1.5 rounded-lg transition-colors shadow-sm"
                                    onclick="confirmSetujuiPerpus({{ $p->id }}, '{{ addslashes($p->buku->judul) }}', '{{ addslashes($p->user->name) }}', {{ $p->jumlah ?? 1 }})">
                                    Setujui
                                </button>
                            </form>
                            <form method="POST" action="{{ route('perpustakaan.admin.peminjaman.tolak', $p->id) }}" id="form-tolak-{{ $p->id }}">
                                @csrf
                                <button type="button" class="text-xs bg-red-600 text-white hover:bg-red-700 font-semibold px-3 py-1.5 rounded-lg transition-colors shadow-sm"
                                    onclick="confirmTolakPerpus({{ $p->id }}, '{{ addslashes($p->buku->judul) }}', '{{ addslashes($p->user->name) }}')">
                                    Tolak
                                </button>
                            </form>
                        </div>
                        @elseif($p->status === 'menunggu_perpanjangan')
                            <form action="{{ route('perpustakaan.admin.peminjaman.perpanjang', $p->id) }}" method="POST" class="inline-block" id="form-perpanjang-{{ $p->id }}">
                                @csrf
                                <button type="button" onclick="confirmPerpanjang({{ $p->id }}, '{{ addslashes($p->user->name) }}', '{{ addslashes($p->buku->judul) }}')" class="text-xs bg-blue-600 text-white hover:bg-blue-700 font-semibold px-3 py-1.5 rounded-lg transition-colors shadow-sm flex items-center gap-1.5 mx-auto">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Konfirmasi Perpanjang
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="table-td text-center text-slate-400 py-10">Belum ada data peminjaman.</td>
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
    function confirmSetujuiPerpus(id, judulBuku, namaSiswa, jumlah) {
        Swal.fire({
            title: 'Setujui Peminjaman?',
            html: `Anda akan menyetujui peminjaman buku <strong class="text-green-700">"${judulBuku}"</strong> oleh <strong>${namaSiswa}</strong>.<br><br><span class="text-xs text-slate-500">Stok buku akan berkurang ${jumlah} eksemplar secara otomatis.</span>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#64748b',
            confirmButtonText: '✓ Ya, Setujui!',
            cancelButtonText: 'Batal',
            background: '#ffffff',
            customClass: { popup: 'rounded-2xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-setujui-' + id).submit();
            }
        });
    }

    function confirmTolakPerpus(id, judulBuku, namaSiswa) {
        Swal.fire({
            title: 'Tolak Peminjaman?',
            html: `Anda akan menolak pengajuan peminjaman buku <strong class="text-red-700">"${judulBuku}"</strong> oleh <strong>${namaSiswa}</strong>.<br><br><span class="text-xs text-slate-500">Stok buku tidak akan berubah.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '✕ Ya, Tolak!',
            cancelButtonText: 'Batal',
            background: '#ffffff',
            customClass: { popup: 'rounded-2xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-tolak-' + id).submit();
            }
        });
    }

    function confirmPerpanjang(id, namaSiswa, judulBuku) {
        Swal.fire({
            title: 'Konfirmasi Perpanjangan?',
            html: `Apakah Anda yakin ingin mengkonfirmasi perpanjangan peminjaman buku <br><strong class="text-green-700">"${judulBuku}"</strong><br> oleh <strong>${namaSiswa}</strong> selama 7 hari?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Konfirmasi!',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-2xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-perpanjang-' + id).submit();
            }
        })
    }
</script>
@endsection
