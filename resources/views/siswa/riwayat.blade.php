@extends('layouts.app')
@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Riwayat Peminjaman Saya')
@section('page-subtitle', 'Daftar seluruh peminjaman alat bengkel Anda')

@section('content')
<div class="card">
    <!-- Filter & Action -->
    <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row gap-4 justify-between items-stretch sm:items-center">
        <form method="GET" class="flex gap-2 items-center flex-1">
            <select name="status" class="flex-1 sm:flex-none border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="menunggu_persetujuan" {{ request('status') === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="dipinjam"     {{ request('status') === 'dipinjam'     ? 'selected' : '' }}>Sedang Dipinjam</option>
                <option value="menunggu_konfirmasi" {{ request('status') === 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
            </select>
            <button type="submit" class="btn-primary">Filter</button>
            @if(request('status'))
            <a href="{{ route('siswa.riwayat') }}" class="px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-600 hover:bg-slate-50">Reset</a>
            @endif
        </form>

        <a href="{{ route('siswa.peminjaman.create') }}" class="btn-primary whitespace-nowrap justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Pinjam Alat Baru
        </a>
    </div>

    {{-- Banner: peminjaman menunggu persetujuan --}}
    @php
        $menungguCount = $peminjamans->getCollection()->where('status', 'menunggu_persetujuan')->count();
        $hampirJatuhTempoItems = $peminjamans->getCollection()->filter(function($p) {
            return $p->status === 'dipinjam'
                && $p->batas_kembali
                && now()->startOfDay()->diffInDays($p->batas_kembali, false) === 1;
        });
    @endphp

    @if($menungguCount > 0)
    <div class="mx-5 mt-4 flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl text-sm">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
        <div>
            <strong>⏳ {{ $menungguCount }} pengajuan peminjaman sedang menunggu konfirmasi admin.</strong>
            <p class="text-xs text-amber-700 mt-0.5">Harap bersabar, admin akan memproses pengajuan Anda secepatnya. Anda akan mendapat notifikasi saat disetujui atau ditolak.</p>
        </div>
    </div>
    @endif
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="table-th">No</th>
                    <th class="table-th">Nama Alat</th>
                    <th class="table-th">Kode</th>
                    <th class="table-th">Jumlah</th>
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
                <tr class="hover:bg-slate-50 transition-colors {{ $p->status === 'dipinjam' ? 'bg-amber-50/30' : '' }}">
                    <td class="table-td text-slate-400">{{ $peminjamans->firstItem() + $loop->index }}</td>
                    <td class="table-td font-semibold text-slate-800">{{ $p->barang->nama_barang }}</td>
                    <td class="table-td">
                        <span class="font-mono text-xs bg-slate-100 px-2 py-0.5 rounded">{{ $p->barang->kode_barang }}</span>
                    </td>
                    <td class="table-td">{{ $p->jumlah }}</td>
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
                    <td class="table-td">
                        @if($p->tanggal_kembali)
                            {{ $p->tanggal_kembali->format('d/m/Y') }}
                        @else
                            <span class="text-slate-400 italic text-xs">Belum dikembalikan</span>
                        @endif
                    </td>
                    <td class="table-td">
                        @if($p->isTerlambat())
                            <span class="badge-diperbaiki !bg-red-100 !text-red-700">⚠ Terlambat</span>
                        @elseif($p->status === 'menunggu_persetujuan')
                            <span class="badge-menunggu !bg-amber-100 !text-amber-700">⏳ Menunggu Persetujuan</span>
                        @elseif($p->status === 'dipinjam')
                            <span class="badge-dipinjam">⏳ Sedang Dipinjam</span>
                        @elseif($p->status === 'menunggu_konfirmasi')
                            <span class="badge-menunggu">⏳ Menunggu Konfirmasi</span>
                        @else
                            <span class="badge-dikembalikan">✓ Sudah Kembali</span>
                        @endif
                    </td>
                    <td class="table-td text-slate-500 text-xs max-w-xs truncate">{{ $p->catatan ?? '—' }}</td>
                    <td class="table-td text-center">
                        @if($p->status === 'dipinjam')
                        <form method="POST" action="{{ route('siswa.peminjaman.kembali', $p->id) }}" class="flex justify-center kembali-form" data-nama="{{ $p->barang->nama_barang }}">
                            @csrf
                            <button type="submit" class="btn-info" title="Kembalikan Barang">
                                Kembalikan
                            </button>
                        </form>
                        @elseif($p->status === 'menunggu_persetujuan')
                        <span class="text-xs text-amber-600 font-semibold bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">⏳ Menunggu Admin</span>
                        @elseif($p->status === 'menunggu_konfirmasi')
                        <span class="text-xs text-amber-600 font-semibold bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">Pending Admin</span>
                        @else
                        <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="table-td text-center text-slate-400 py-12">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Belum ada riwayat peminjaman.
                        <a href="{{ route('siswa.peminjaman.create') }}" class="text-blue-600 hover:underline ml-1">Pinjam alat sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($peminjamans->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">{{ $peminjamans->links() }}</div>
    @endif
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kembaliForms = document.querySelectorAll('.kembali-form');
        kembaliForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const namaAlat = this.getAttribute('data-nama');
                Swal.fire({
                    title: 'Konfirmasi Pengembalian',
                    text: 'Apakah Anda yakin ingin mengembalikan ' + namaAlat + '?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Kembalikan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

        // Peringatan otomatis jika ada alat yang hampir jatuh tempo (H-1)
        @if($hampirJatuhTempoItems->count() > 0)
        @foreach($hampirJatuhTempoItems as $ht)
        setTimeout(function() {
            Swal.fire({
                title: '<span style="color:#b45309;font-size:1rem;font-weight:700">⚠️ Pengingat Pengembalian!</span>',
                html: `
                    <div style="text-align:left;font-size:0.85rem">
                        <p style="color:#374151;margin-bottom:10px">Alat berikut harus dikembalikan <strong style="color:#dc2626">besok</strong>:</p>
                        <div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:8px;padding:10px 14px;margin-bottom:10px">
                            <p style="font-weight:700;color:#92400e;margin:0">🔧 {{ $ht->barang->nama_barang }}</p>
                            <p style="color:#78350f;font-size:0.8rem;margin:4px 0 0">Batas Kembali: <strong>{{ $ht->batas_kembali->translatedFormat('d F Y') }}</strong></p>
                        </div>
                        <p style="color:#6b7280;font-size:0.8rem">Segera kembalikan ke admin bengkel sebelum batas waktu untuk menghindari denda!</p>
                    </div>
                `,
                icon: 'warning',
                confirmButtonText: '✅ Oke, Saya Mengerti',
                confirmButtonColor: '#f59e0b',
                width: '420px',
            });
        }, 800);
        @endforeach
        @endif
    });

</script>
@endpush
@endsection
