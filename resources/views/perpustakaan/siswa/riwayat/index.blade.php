@extends('perpustakaan.layouts.app')

@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Riwayat Peminjaman Saya')
@section('page-subtitle', 'Semua riwayat pinjam buku kamu')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div class="mb-5 flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl shadow-sm">
    <svg class="w-5 h-5 mt-0.5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <p class="text-sm font-medium">{{ session('success') }}</p>
</div>
@endif
@if(session('error'))
<div class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl shadow-sm">
    <svg class="w-5 h-5 mt-0.5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    <p class="text-sm font-medium">{{ session('error') }}</p>
</div>
@endif

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="table-th">No</th>
                    <th class="table-th">Buku</th>
                    <th class="table-th text-center">Jml</th>
                    <th class="table-th">Tgl Pinjam</th>
                    <th class="table-th">Batas Kembali</th>
                    <th class="table-th">Tgl Dikembalikan</th>
                    <th class="table-th">Status</th>
                    <th class="table-th text-right">Denda</th>
                    <th class="table-th text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($peminjamans as $i => $p)
                <tr class="hover:bg-slate-50 transition-colors {{ $p->isTerlambat() ? 'bg-red-50/40' : '' }}">
                    <td class="table-td text-slate-400 text-xs">{{ $peminjamans->firstItem() + $i }}</td>
                    <td class="table-td">
                        <div class="font-semibold text-slate-800">{{ $p->buku->judul }}</div>
                        <div class="text-xs text-slate-500">{{ $p->buku->penulis }}</div>
                        @if($p->buku->kategori)
                            <span class="text-xs text-green-600 font-medium">{{ $p->buku->kategori }}</span>
                        @endif
                    </td>
                    <td class="table-td text-center font-bold text-slate-700">{{ $p->jumlah ?? 1 }}</td>
                    <td class="table-td text-slate-600 text-sm">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="table-td">
                        <span class="{{ $p->isTerlambat() ? 'text-red-600 font-bold' : ($p->status === 'dipinjam' ? 'text-amber-600' : 'text-slate-600') }} text-sm">
                            {{ $p->batas_kembali->format('d/m/Y') }}
                        </span>
                    </td>
                    <td class="table-td text-slate-600 text-sm">
                        @if($p->pengembalian)
                            {{ $p->pengembalian->tanggal_kembali->format('d/m/Y') }}
                        @else
                            <span class="text-slate-400 text-xs">Belum dikembalikan</span>
                        @endif
                    </td>
                    <td class="table-td">
                        @if($p->status === 'dikembalikan')
                            <span class="badge-dikembalikan">✓ Dikembalikan</span>
                        @elseif($p->status === 'menunggu_konfirmasi')
                            <span class="badge-menunggu">⏳ Menunggu Konfirmasi</span>
                        @elseif($p->status === 'menunggu_perpanjangan')
                            <span class="badge-menunggu">⏳ Menunggu Perpanjangan Admin</span>
                        @elseif($p->isTerlambat())
                            <span class="badge-terlambat">⚠ Terlambat</span>
                        @else
                            <span class="badge-dipinjam">Dipinjam</span>
                        @endif
                    </td>
                    <td class="table-td text-right">
                        @if($p->pengembalian)
                            @if($p->pengembalian->denda > 0)
                                <div class="text-red-600 font-bold text-sm">{{ $p->pengembalian->dendaFormatted() }}</div>
                                <div class="text-xs text-red-400">{{ $p->pengembalian->hari_terlambat }} hari telat</div>
                                <div class="text-[10px] text-amber-600 mt-1.5 leading-tight font-medium bg-amber-50 border border-amber-200 rounded-md px-1.5 py-0.5 inline-block text-right">Silakan bayar ke admin</div>
                            @else
                                <span class="text-green-600 text-sm font-semibold">Rp 0</span>
                            @endif
                        @elseif($p->isTerlambat())
                            <div class="text-orange-500 text-xs font-semibold">
                                ~Rp {{ number_format($p->hariTerlambatSekarang() * 1000, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-orange-400">estimasi</div>
                            <div class="text-[10px] text-orange-600 mt-1.5 leading-tight font-medium bg-orange-50 border border-orange-200 rounded-md px-1.5 py-0.5 inline-block text-right">Bayar ke admin saat kembali</div>
                        @else
                            <span class="text-slate-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="table-td text-center">
                        @if($p->status === 'dipinjam')
                            <div class="flex flex-col items-center gap-2">
                                {{-- Tombol Kembalikan --}}
                                <button
                                    onclick="openModal({{ $p->id }}, '{{ addslashes($p->buku->judul) }}', {{ $p->isTerlambat() ? 'true' : 'false' }}, {{ $p->hariTerlambatSekarang() }})"
                                    class="inline-flex items-center justify-center gap-1.5 w-full px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                    Kembalikan
                                </button>

                                @if($p->bisaDiperpanjang())
                                    <form action="{{ route('perpustakaan.siswa.peminjaman.perpanjang', $p->id) }}" method="POST" id="form-perpanjang-{{ $p->id }}" class="w-full">
                                        @csrf
                                        <button type="button" onclick="confirmPerpanjang({{ $p->id }})" class="inline-flex items-center justify-center gap-1.5 w-full px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Perpanjang
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @elseif($p->status === 'menunggu_konfirmasi')
                            <span class="text-xs text-amber-600 font-semibold bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">Pending Admin</span>
                        @else
                            <span class="text-slate-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="table-td text-center text-slate-400 py-12">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                        Kamu belum pernah meminjam buku.
                        <div class="mt-2">
                            <a href="{{ route('perpustakaan.siswa.peminjaman.create') }}" class="text-green-600 font-semibold hover:underline">Pinjam buku sekarang →</a>
                        </div>
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

{{-- Modal Konfirmasi Pengembalian --}}
<div id="modal-kembali" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModal()"></div>

    {{-- Modal Card --}}
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl z-10 overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-green-700 to-green-600 px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg">Kembalikan Buku</h3>
                    <p class="text-green-200 text-sm">Konfirmasi pengembalian</p>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5">
            <p class="text-slate-600 text-sm mb-1">Buku yang akan dikembalikan:</p>
            <p id="modal-judul" class="font-bold text-slate-800 text-base mb-4"></p>

            {{-- Alert terlambat --}}
            <div id="modal-alert-terlambat" class="hidden mb-4 flex items-start gap-2.5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <div>
                    <p class="font-semibold">Buku terlambat dikembalikan!</p>
                    <p id="modal-denda-text" class="mt-0.5"></p>
                    <p class="text-xs text-red-600 mt-1.5 font-semibold">⚠️ Silakan bayar nominal denda tersebut langsung ke petugas/admin perpustakaan.</p>
                </div>
            </div>

            {{-- Info normal --}}
            <div id="modal-alert-normal" class="mb-4 flex items-center gap-2.5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <p>Pengembalian tepat waktu. <strong>Tidak ada denda.</strong></p>
            </div>

            <p class="text-xs text-slate-400">Tanggal kembali akan dicatat sebagai <strong>hari ini</strong>. Tindakan ini tidak dapat dibatalkan.</p>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex gap-3">
            <form id="form-kembali" method="POST" action="" class="flex-1">
                @csrf
                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Ya, Kembalikan Sekarang
                </button>
            </form>
            <button onclick="closeModal()"
                class="px-4 py-2.5 border border-slate-200 text-slate-600 font-medium rounded-xl hover:bg-slate-100 transition text-sm">
                Batal
            </button>
        </div>
    </div>
</div>

<script>
const modal = document.getElementById('modal-kembali');

function openModal(id, judul, terlambat, hariTerlambat) {
    document.getElementById('modal-judul').textContent = judul;
    document.getElementById('form-kembali').action =
        `/perpustakaan/siswa/peminjaman/${id}/kembali`;

    const alertTerlambat = document.getElementById('modal-alert-terlambat');
    const alertNormal    = document.getElementById('modal-alert-normal');

    if (terlambat) {
        const denda = hariTerlambat * 1000;
        const dendaFmt = new Intl.NumberFormat('id-ID').format(denda);
        document.getElementById('modal-denda-text').textContent =
            `Terlambat ${hariTerlambat} hari. Estimasi denda: Rp ${dendaFmt}`;
        alertTerlambat.classList.remove('hidden');
        alertTerlambat.classList.add('flex');
        alertNormal.classList.add('hidden');
    } else {
        alertTerlambat.classList.add('hidden');
        alertTerlambat.classList.remove('flex');
        alertNormal.classList.remove('hidden');
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

function confirmPerpanjang(id) {
    Swal.fire({
        title: 'Ajukan Perpanjangan?',
        text: "Pengajuan perpanjangan buku akan dikirim ke admin. Setelah ini, silakan temui admin perpustakaan dengan membawa buku fisik untuk dikonfirmasi.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Ajukan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-perpanjang-' + id).submit();
        }
    });
}

// Tutup modal dengan Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeModal();
});
</script>
@endsection
