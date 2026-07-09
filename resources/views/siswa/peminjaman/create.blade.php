@extends('layouts.app')
@section('title', 'Keranjang Peminjaman')
@section('page-title', 'Checkout Peminjaman Alat')
@section('page-subtitle', 'Periksa keranjang dan ajukan peminjaman')

@section('content')
<div class="max-w-3xl">

    <!-- Info Regulasi 5 Hari -->
    <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-xl text-sm mb-6">
        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
        <div>
            <strong>Regulasi Peminjaman:</strong>
            Sesuai peraturan sekolah, batas maksimal peminjaman alat bengkel adalah <strong>5 hari</strong> sejak tanggal peminjaman. Harap kembalikan tepat waktu!
        </div>
    </div>

    @if(empty($cartItems) || count($cartItems) == 0)
    <div class="card p-8 text-center">
        <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <p class="text-slate-500 font-medium mb-4">Keranjang Anda kosong.</p>
        <a href="{{ route('siswa.dashboard') }}" class="btn-primary inline-flex justify-center">Kembali ke Katalog</a>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- List Barang -->
        <div class="lg:col-span-2 space-y-4">
            <h2 class="font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Daftar Alat ({{ count($cartItems) }})
            </h2>
            
            <div class="card divide-y divide-slate-100">
                @foreach($cartItems as $barang)
                <div class="p-4 flex items-center justify-between gap-4" id="cart-item-{{ $barang->id }}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-800 text-sm leading-tight">{{ $barang->nama_barang }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">Kode: {{ $barang->kode_barang }}</p>
                            <p class="text-[10px] text-blue-600 font-semibold mt-1">Stok Tersedia: {{ $barang->stok }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <!-- Qty Selector -->
                        <div class="flex items-center bg-slate-100 rounded-lg p-1">
                            <button type="button" id="btn-min-{{ $barang->id }}" onclick="updateQty({{ $barang->id }}, -1, {{ $barang->stok }})" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:bg-white rounded shadow-sm transition-colors" {{ $cart[$barang->id] <= 1 ? 'disabled' : '' }}>
                                -
                            </button>
                            <span id="qty-val-{{ $barang->id }}" class="w-8 text-center text-sm font-semibold text-slate-700">{{ $cart[$barang->id] }}</span>
                            <button type="button" id="btn-plus-{{ $barang->id }}" onclick="updateQty({{ $barang->id }}, 1, {{ $barang->stok }})" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:bg-white rounded shadow-sm transition-colors" {{ $cart[$barang->id] >= $barang->stok ? 'disabled' : '' }}>
                                +
                            </button>
                        </div>

                        <button type="button" onclick="removeFromCart({{ $barang->id }})" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors flex-shrink-0" title="Hapus dari keranjang">
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
            <div class="card p-5">
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-xs mb-4">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('siswa.peminjaman.store') }}" id="checkout-form">
                    @csrf
                    
                    <!-- Tanggal Pinjam -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Tanggal Ambil <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                            value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                            min="{{ date('Y-m-d') }}"
                            required
                            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            onchange="updateBatasKembali()">
                    </div>

                    <!-- Batas Kembali Preview -->
                    <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                        <p class="text-xs font-semibold text-amber-700 mb-0.5">Batas Pengembalian (Maks)</p>
                        <p class="font-bold text-amber-900 text-sm" id="batas_kembali_preview">-</p>
                        <p class="text-[10px] text-amber-600 mt-1">Sesuai regulasi 5 hari</p>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Catatan / Keperluan <span class="text-slate-400 font-normal text-xs">(opsional)</span>
                        </label>
                        <textarea name="catatan" rows="3"
                            placeholder="Contoh: Praktikum mesin"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('catatan') }}</textarea>
                    </div>

                    <button type="button" onclick="showPeminjamanAlert()" class="btn-primary w-full justify-center py-2.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Ajukan Peminjaman
                    </button>
                    <a href="{{ route('siswa.dashboard') }}" class="block text-center mt-3 text-sm text-slate-500 hover:text-blue-600 font-medium">
                        Kembali ke Katalog
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

    function removeFromCart(barangId) {
        // Optimistic UI update
        const itemEl = document.getElementById('cart-item-' + barangId);
        if(itemEl) itemEl.style.opacity = '0.5';

        fetch(`/siswa/keranjang/${barangId}`, {
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
                // If cart is empty, reload to show empty state
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

    function updateQty(barangId, increment, maxStok) {
        const qtyValEl = document.getElementById('qty-val-' + barangId);
        const btnMin = document.getElementById('btn-min-' + barangId);
        const btnPlus = document.getElementById('btn-plus-' + barangId);
        
        if (!qtyValEl) return;
        let currentQty = parseInt(qtyValEl.innerText);
        let newQty = currentQty + increment;

        if (newQty < 1 || newQty > maxStok) return;

        // Optimistic UI Update (Loading state)
        qtyValEl.innerText = '...';
        btnMin.disabled = true;
        btnPlus.disabled = true;
        
        fetch(`/siswa/keranjang/update/${barangId}`, {
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
                // Update DOM on success
                qtyValEl.innerText = newQty;
                btnMin.disabled = (newQty <= 1);
                btnPlus.disabled = (newQty >= maxStok);
            } else {
                Swal.fire('Gagal', data.message, 'error');
                // Revert
                qtyValEl.innerText = currentQty;
                btnMin.disabled = (currentQty <= 1);
                btnPlus.disabled = (currentQty >= maxStok);
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
            // Revert
            qtyValEl.innerText = currentQty;
            btnMin.disabled = (currentQty <= 1);
            btnPlus.disabled = (currentQty >= maxStok);
        });
    }

    function updateBatasKembali() {
        const input = document.getElementById('tanggal_pinjam');
        const preview = document.getElementById('batas_kembali_preview');
        
        if (input && input.value) {
            const date = new Date(input.value);
            // Tambah 5 hari
            date.setDate(date.getDate() + 5);
            
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            preview.textContent = date.toLocaleDateString('id-ID', options);
        }
    }

    // Init on load
    document.addEventListener('DOMContentLoaded', updateBatasKembali);

    function showPeminjamanAlert() {
        Swal.fire({
            title: '<span style="font-size:1.1rem;font-weight:700;color:#1e293b">⚠️ Peraturan Peminjaman Alat</span>',
            html: `
                <div style="text-align:left;font-size:0.82rem;line-height:1.6">
                    <p style="color:#475569;margin-bottom:10px">Harap baca dan pahami peraturan berikut sebelum mengajukan peminjaman:</p>

                    <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:10px;padding:12px 14px;margin-bottom:12px">
                        <p style="font-weight:700;color:#0369a1;margin-bottom:8px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em">📋 Peraturan Peminjaman</p>
                        <ol style="margin:0;padding-left:1.2rem;color:#1e40af;space-y:4px">
                            <li style="margin-bottom:5px">Peminjam wajib mengembalikan alat dalam <strong>kondisi semula</strong> (tidak rusak/hilang).</li>
                            <li style="margin-bottom:5px">Alat <strong>tidak diperkenankan dibawa keluar</strong> lingkungan sekolah tanpa seizin toolman.</li>
                            <li style="margin-bottom:5px">Maksimal waktu peminjaman adalah <strong>5 Hari Kerja</strong> sejak tanggal peminjaman.</li>
                        </ol>
                    </div>

                    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 14px">
                        <p style="font-weight:700;color:#dc2626;margin-bottom:8px;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em">💰 Ketentuan Denda</p>
                        <div style="display:flex;align-items:flex-start;gap:8px;color:#991b1b">
                            <span style="font-size:1.2rem;flex-shrink:0">⚠️</span>
                            <p style="margin:0">Apabila alat yang dipinjam <strong>hilang</strong>, peminjam wajib membayar denda sebesar <strong>harga alat yang dipinjam</strong> kepada admin/toolman.</p>
                        </div>
                    </div>

                    <p style="margin-top:12px;color:#64748b;font-size:0.78rem;text-align:center">Dengan mengklik <strong>"Ya, Saya Setuju"</strong>, Anda menyatakan telah membaca dan menyetujui seluruh peraturan di atas.</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '✅ Ya, Saya Setuju',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            focusConfirm: false,
            customClass: {
                popup: 'swal-peminjaman-popup',
                title: 'swal-peminjaman-title',
            },
            width: '480px',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('checkout-form').submit();
            }
        });
    }
</script>
@endpush
@endsection
