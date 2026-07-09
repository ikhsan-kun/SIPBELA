@extends('layouts.app')
@section('title', 'Manajemen Peminjaman')
@section('page-title', 'Manajemen Peminjaman')
@section('page-subtitle', 'Kelola transaksi peminjaman dan proses pengembalian')
@section('content')
<div class="card">
    <!-- Filter Header -->
    <div class="px-5 py-4 border-b border-slate-100">
        <form method="GET" class="flex flex-wrap gap-2 items-center">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama siswa / barang..."
                class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-48">
            <select name="status" class="border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="dipinjam"     {{ request('status') === 'dipinjam'     ? 'selected' : '' }}>Dipinjam</option>
                <option value="menunggu_persetujuan" {{ request('status') === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="menunggu_konfirmasi" {{ request('status') === 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="btn-primary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Filter
            </button>
            @if(request()->hasAny(['status', 'search']))
            <a href="{{ route('admin.peminjaman.index') }}" class="px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 hover:bg-slate-50">Reset</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="table-th">No</th>
                    <th class="table-th">Siswa</th>
                    <th class="table-th">Barang</th>
                    <th class="table-th text-center">Jml</th>
                    <th class="table-th">Tgl Pinjam</th>
                    <th class="table-th">Batas Kembali</th>
                    <th class="table-th">Tgl Kembali</th>
                    <th class="table-th">Status</th>
                    <th class="table-th">Catatan</th>
                    <th class="table-th text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($peminjamans as $p)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="table-td text-slate-400">{{ $peminjamans->firstItem() + $loop->index }}</td>
                    <td class="table-td">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($p->user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-slate-800">{{ $p->user->name }}</span>
                        </div>
                    </td>
                    <td class="table-td font-medium">{{ $p->barang->nama_barang }}</td>
                    <td class="table-td text-center font-bold text-slate-700">{{ $p->jumlah ?? 1 }}</td>
                    <td class="table-td">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="table-td">
                        @if($p->batas_kembali)
                            <span class="{{ $p->isTerlambat() ? 'text-red-600 font-bold' : '' }}">
                                {{ $p->batas_kembali->format('d/m/Y') }}
                                @if($p->isTerlambat())
                                    <br><span class="text-[10px] text-red-500">(Telat {{ $p->hariTerlambatSekarang() }} hari)</span>
                                @endif
                            </span>
                        @else
                            <span class="text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="table-td">{{ $p->tanggal_kembali ? $p->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                    <td class="table-td">
                        @if($p->isTerlambat() && $p->status === 'dipinjam')
                            <span class="badge-diperbaiki !bg-red-100 !text-red-700">⚠ Terlambat</span>
                        @elseif($p->status === 'dipinjam')
                            <span class="badge-dipinjam">⏳ Dipinjam</span>
                        @elseif($p->status === 'menunggu_persetujuan')
                            <span class="badge-menunggu !bg-yellow-100 !text-yellow-700">⌛ Menunggu Persetujuan</span>
                        @elseif($p->status === 'menunggu_konfirmasi')
                            <span class="badge-menunggu">⏳ Menunggu Konfirmasi</span>
                        @elseif($p->status === 'ditolak')
                            <span class="badge-diperbaiki !bg-red-100 !text-red-700">❌ Ditolak</span>
                        @else
                            <span class="badge-dikembalikan">✓ Dikembalikan</span>
                        @endif
                    </td>
                    <td class="table-td text-slate-400 max-w-[150px] truncate">{{ $p->catatan ?? '-' }}</td>
                    <td class="table-td text-center">
                        @if($p->status === 'menunggu_persetujuan')
                        <div class="flex items-center justify-center gap-2">
                            <form method="POST" action="{{ route('admin.peminjaman.setujui', $p->id) }}" id="form-setujui-{{ $p->id }}">
                                @csrf
                                <button type="button" class="btn-success" title="Setujui"
                                    onclick="confirmSetujui({{ $p->id }}, '{{ addslashes($p->barang->nama_barang) }}', '{{ addslashes($p->user->name) }}', {{ $p->jumlah ?? 1 }})">
                                    Setujui
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.peminjaman.tolak', $p->id) }}" id="form-tolak-{{ $p->id }}">
                                @csrf
                                <button type="button" class="btn-primary !bg-red-600 hover:!bg-red-700" title="Tolak"
                                    onclick="confirmTolak({{ $p->id }}, '{{ addslashes($p->barang->nama_barang) }}', '{{ addslashes($p->user->name) }}')">
                                    Tolak
                                </button>
                            </form>
                        </div>
                        @elseif(in_array($p->status, ['dipinjam', 'menunggu_konfirmasi']))
                        <form id="form-kembali-{{ $p->id }}" method="POST" action="{{ route('admin.peminjaman.kembali', $p->id) }}" class="flex justify-center">
                            @csrf
                            <button type="button" onclick="confirmKembaliBengkel({{ $p->id }}, '{{ addslashes($p->barang->nama_barang) }}', '{{ addslashes($p->user->name) }}', '{{ $p->status }}', {{ $p->jumlah ?? 1 }})" class="{{ $p->status === 'menunggu_konfirmasi' ? 'btn-primary' : 'btn-success' }}" title="{{ $p->status === 'menunggu_konfirmasi' ? 'Konfirmasi Kembali' : 'Proses Kembali' }}">
                                {{ $p->status === 'menunggu_konfirmasi' ? 'Konfirmasi' : 'Proses Kembali' }}
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="table-td text-center text-slate-400 py-10">
                        Tidak ada data peminjaman ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($peminjamans->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">
        {{ $peminjamans->links() }}
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmSetujui(id, namaBarang, namaSiswa, jumlah) {
    Swal.fire({
        title: 'Setujui Peminjaman?',
        html: `Anda akan menyetujui peminjaman <strong class="text-green-700">${jumlah} unit ${namaBarang}</strong> oleh <strong>${namaSiswa}</strong>.<br><br><span class="text-xs text-slate-500">Stok barang akan berkurang ${jumlah} unit secara otomatis.</span>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
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

function confirmTolak(id, namaBarang, namaSiswa) {
    Swal.fire({
        title: 'Tolak Peminjaman?',
        html: `Anda akan menolak pengajuan peminjaman <strong class="text-red-700">${namaBarang}</strong> oleh <strong>${namaSiswa}</strong>.<br><br><span class="text-xs text-slate-500">Stok barang tidak akan berubah.</span>`,
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

function confirmKembaliBengkel(id, namaBarang, namaSiswa, status, jumlah) {
    let confirmText = status === 'menunggu_konfirmasi' ? 'Ya, Konfirmasi Kembali!' : 'Ya, Proses Kembali!';
    let actionText = status === 'menunggu_konfirmasi' ? 'mengonfirmasi pengembalian' : 'memproses pengembalian';
    Swal.fire({
        title: 'Konfirmasi Pengembalian',
        html: `Apakah Anda yakin ingin ${actionText} <strong class="text-blue-600">${jumlah} unit ${namaBarang}</strong> oleh <strong>${namaSiswa}</strong>?<br><br><span class="text-xs text-slate-500">Stok barang akan bertambah ${jumlah} secara otomatis.</span>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981', // green-500
        cancelButtonColor: '#64748b', // slate-500
        confirmButtonText: confirmText,
        cancelButtonText: 'Batal',
        background: '#ffffff',
        customClass: {
            popup: 'rounded-2xl',
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`form-kembali-${id}`).submit();
        }
    });
}
</script>
@endsection
