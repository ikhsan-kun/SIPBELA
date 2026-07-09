@extends('perpustakaan.layouts.app')
@section('title', 'Keranjang Perpustakaan')
@section('page-title', 'Checkout Peminjaman Buku')
@section('page-subtitle', 'Periksa buku dan ajukan peminjaman')

@section('content')
<div class="max-w-3xl">

    <!-- Info Regulasi -->
    <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm mb-6">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
        <div>
            <strong>Aturan Perpustakaan:</strong>
            Masa peminjaman buku maksimal <strong>7 hari</strong>. Keterlambatan dikenakan denda Rp 1.000/hari per buku.
        </div>
    </div>

    @if(empty($cartItems) || count($cartItems) == 0)
    <div class="card p-8 text-center">
        <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
        <p class="text-slate-500 font-medium mb-4">Keranjang buku Anda kosong.</p>
        <a href="{{ route('perpustakaan.siswa.dashboard') }}" class="btn-perpus inline-flex justify-center">Kembali ke Dashboard</a>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- List Buku -->
        <div class="lg:col-span-2 space-y-4">
            <h2 class="font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Buku yang Dipilih ({{ count($cartItems) }})
            </h2>
            
            <div class="card divide-y divide-slate-100">
                @foreach($cartItems as $buku)
                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4" id="cart-item-{{ $buku->id }}">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-12 h-16 rounded overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0">
                            @if($buku->gambar)
                                <img src="{{ asset('storage/' . $buku->gambar) }}" alt="{{ $buku->judul }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-800 text-sm leading-tight truncate">{{ $buku->judul }}</p>
                            <p class="text-xs text-slate-500 mt-0.5 truncate">{{ $buku->penulis }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">ISBN: {{ $buku->isbn ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto mt-2 sm:mt-0 pt-2 sm:pt-0 border-t sm:border-0 border-slate-100">
                        <div class="flex items-center bg-slate-100 rounded-lg p-1">
                            <button type="button" onclick="updateCart({{ $buku->id }}, -1)" class="w-8 h-8 flex items-center justify-center bg-white rounded shadow-sm text-slate-600 hover:text-green-600 hover:bg-green-50 transition-colors">
                                -
                            </button>
                            <span id="qty-{{ $buku->id }}" class="w-10 text-center text-sm font-bold text-slate-800">{{ $cart[$buku->id] ?? 1 }}</span>
                            <button type="button" onclick="updateCart({{ $buku->id }}, 1)" class="w-8 h-8 flex items-center justify-center bg-white rounded shadow-sm text-slate-600 hover:text-green-600 hover:bg-green-50 transition-colors">
                                +
                            </button>
                        </div>
                        <button type="button" onclick="removeFromCart({{ $buku->id }})" class="text-red-500 hover:bg-red-50 p-2.5 rounded-lg transition-colors flex-shrink-0" title="Hapus dari keranjang">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Form Checkout -->
        <div>
            <h2 class="font-bold text-slate-800 mb-4">Detail Peminjaman</h2>
            <div class="card p-5 border-t-4 border-t-green-500">
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-xs mb-4">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('perpustakaan.siswa.peminjaman.store') }}" id="checkout-form">
                    @csrf
                    
                    <!-- Tanggal Pinjam -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Tanggal Pinjam <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                            value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                            min="{{ date('Y-m-d') }}"
                            required
                            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                            onchange="updateBatasKembali()">
                    </div>

                    <!-- Batas Kembali Preview -->
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl">
                        <p class="text-xs font-semibold text-green-700 mb-0.5">Batas Pengembalian</p>
                        <p class="font-bold text-green-900 text-sm" id="batas_kembali_preview">-</p>
                        <p class="text-[10px] text-green-600 mt-1">Sesuai regulasi 7 hari</p>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Catatan <span class="text-slate-400 font-normal text-xs">(opsional)</span>
                        </label>
                        <textarea name="catatan" rows="2"
                            placeholder="Tinggalkan pesan..."
                            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none">{{ old('catatan') }}</textarea>
                    </div>

                    <button type="submit" class="btn-perpus w-full justify-center py-2.5 shadow-sm">
                        Ajukan Peminjaman
                    </button>
                    <a href="{{ route('perpustakaan.siswa.dashboard') }}" class="block text-center mt-3 text-sm text-slate-500 hover:text-green-600 font-medium">
                        Kembali ke Dashboard
                    </a>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function removeFromCart(bukuId) {
        // Optimistic UI update
        const itemEl = document.getElementById('cart-item-' + bukuId);
        if(itemEl) itemEl.style.opacity = '0.5';

        fetch(`/perpustakaan/siswa/keranjang/${bukuId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if(itemEl) itemEl.remove();
                // Update badge di header jika ada
                const badge = document.getElementById('cart-badge');
                if (badge) badge.innerText = data.cart_count;
                // Jika keranjang kosong, reload untuk tampilkan empty state
                const remainingItems = document.querySelectorAll('[id^="cart-item-"]');
                if (remainingItems.length === 0) {
                    window.location.reload();
                }
            } else {
                Swal.fire('Gagal', data.message, 'error');
                if(itemEl) itemEl.style.opacity = '1';
            }
        })
        .catch(err => {
            console.error(err);
            if(itemEl) itemEl.style.opacity = '1';
        });
    }

    function updateBatasKembali() {
        const input = document.getElementById('tanggal_pinjam');
        const preview = document.getElementById('batas_kembali_preview');
        
        if (input && input.value) {
            const date = new Date(input.value);
            // Tambah 7 hari
            date.setDate(date.getDate() + 7);
            
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            preview.textContent = date.toLocaleDateString('id-ID', options);
        }
    }

    function updateCart(bukuId, change) {
        const qtyEl = document.getElementById('qty-' + bukuId);
        let currentQty = parseInt(qtyEl.innerText);
        let newQty = currentQty + change;
        
        if (newQty < 1) return;

        qtyEl.style.opacity = '0.5';

        fetch(`/perpustakaan/siswa/keranjang/update/${bukuId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ qty: newQty })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                qtyEl.innerText = newQty;
                qtyEl.style.opacity = '1';
                // Update badge di header if exists
                const badge = document.getElementById('cart-badge');
                if (badge) badge.innerText = data.cart_count;
            } else {
                Swal.fire('Gagal', data.message, 'error');
                qtyEl.style.opacity = '1';
            }
        })
        .catch(err => {
            console.error(err);
            qtyEl.style.opacity = '1';
        });
    }

    // Init on load
    document.addEventListener('DOMContentLoaded', updateBatasKembali);
</script>
@endpush
@endsection
