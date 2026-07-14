@extends('layouts.app')
@section('title', 'Katalog Alat Bengkel')
@section('page-title', 'Katalog Alat Bengkel')
@section('page-subtitle', 'Pilih alat yang ingin dipinjam')

@section('content')
<!-- Welcome & Stats -->
<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-slate-500 text-sm">Selamat datang, <span class="font-semibold text-slate-800">{{ auth()->user()->name }}</span></p>
    </div>
    <div class="flex gap-3">
        <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-2 text-center">
            <p class="text-xl font-bold text-amber-600">{{ $stats['sedang_dipinjam'] }}</p>
            <p class="text-xs text-amber-700">Sedang Dipinjam</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-2 text-center">
            <p class="text-xl font-bold text-blue-600">{{ $stats['total_pinjam'] }}</p>
            <p class="text-xs text-blue-700">Total Pinjam</p>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="mb-4 flex flex-col sm:flex-row gap-2">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input id="search-barang" type="text" placeholder="Cari nama alat atau kode (BRG-xxx)..."
            class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white shadow-sm"
            oninput="filterKatalog()">
    </div>
    <div class="flex gap-2">
        <button id="filter-semua" onclick="setFilter('semua')" class="filter-btn filter-active px-3 py-2 rounded-xl text-xs font-semibold border border-blue-500 bg-blue-500 text-white transition-all">Semua</button>
        <button id="filter-tersedia" onclick="setFilter('tersedia')" class="filter-btn px-3 py-2 rounded-xl text-xs font-semibold border border-slate-200 bg-white text-slate-600 hover:border-blue-400 hover:text-blue-600 transition-all">Tersedia</button>
        <button id="filter-na" onclick="setFilter('na')" class="filter-btn px-3 py-2 rounded-xl text-xs font-semibold border border-slate-200 bg-white text-slate-600 hover:border-slate-400 transition-all">N/A</button>
    </div>
</div>

<!-- Catalog Grid -->
<div id="katalog-grid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4">
    @foreach($barangs as $barang)
    @php
        $tersedia = $barang->stok > 0 && $barang->kondisi === 'baik' && !$barang->butuhMaintenance();
    @endphp
    <div class="card p-3 sm:p-4 hover:shadow-md transition-all duration-200 group flex flex-col"
         data-nama="{{ $barang->nama_barang }}"
         data-kode="{{ $barang->kode_barang }}"
         data-tersedia="{{ $tersedia ? '1' : '0' }}">
        <!-- Icon -->
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center mb-3 {{ $tersedia ? 'bg-blue-100' : 'bg-slate-100' }}">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 {{ $tersedia ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>

        <!-- Info -->
        <div class="flex-1">
            <p class="text-[9px] sm:text-xs font-mono text-slate-400 mb-0.5">{{ $barang->kode_barang }}</p>
            <h3 class="font-semibold text-slate-800 text-xs sm:text-sm leading-tight mb-2 min-h-[2rem]">{{ $barang->nama_barang }}</h3>
        </div>

        <!-- Footer -->
        <div class="mt-auto">
            <div class="flex items-center justify-between mb-3">
                <!-- Stok Badge -->
                <div class="flex items-center gap-1">
                    <div class="w-1.5 h-1.5 rounded-full {{ $barang->stok > 2 ? 'bg-emerald-500' : ($barang->stok > 0 ? 'bg-amber-500' : 'bg-red-500') }}"></div>
                    <span class="text-[10px] font-medium {{ $barang->stok > 2 ? 'text-emerald-600' : ($barang->stok > 0 ? 'text-amber-600' : 'text-red-600') }}">
                        S: {{ $barang->stok }}
                    </span>
                </div>
                <span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase">{{ $barang->kondisi }}</span>
            </div>

            <!-- Action -->
            @php
                $tersedia = $barang->stok > 0 && $barang->kondisi === 'baik' && !$barang->butuhMaintenance();
            @endphp

            @if($tersedia)
                @if(array_key_exists($barang->id, $cart))
                <button onclick="addToCart({{ $barang->id }})" class="w-full bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg py-2 text-[10px] sm:text-xs font-semibold transition-colors">
                    Di Keranjang ({{ $cart[$barang->id] }}) <span class="text-xs font-normal">+</span>
                </button>
                @else
                <button onclick="addToCart({{ $barang->id }})" class="btn-primary w-full justify-center text-[10px] sm:text-xs py-2 px-1">
                    + Pinjam
                </button>
                @endif
            @else
                <button disabled class="w-full bg-slate-100 text-slate-400 rounded-lg py-2 text-[10px] sm:text-xs font-semibold cursor-not-allowed">
                    @if($barang->butuhMaintenance())
                        Maintenance
                    @else
                        N/A
                    @endif
                </button>
            @endif
        </div>
    </div>
    @endforeach
</div>

@if($barangs->isEmpty())
<div class="text-center py-16 text-slate-400">
    <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
    <p>Belum ada barang di katalog.</p>
</div>
@endif

<!-- Pesan kosong saat search tidak ada hasil -->
<div id="empty-search-msg" class="hidden text-center py-12 col-span-full">
    <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    <p class="text-slate-400 text-sm font-medium">Tidak ada alat yang cocok dengan pencarian Anda.</p>
    <button onclick="document.getElementById('search-barang').value=''; setFilter('semua');" class="mt-3 text-xs text-blue-600 hover:underline">Reset pencarian</button>
</div>

<!-- Floating Cart Badge -->
<a href="{{ route('siswa.peminjaman.create') }}" id="floating-cart" class="fixed bottom-6 right-6 bg-blue-600 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 transition-all flex items-center justify-center group z-50">
    <div class="relative">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <span id="cart-counter" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white">{{ count($cart) }}</span>
    </div>
    <span class="ml-2 hidden group-hover:block whitespace-nowrap font-medium pr-1">Checkout</span>
</a>

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let activeFilter = 'semua';

    function setFilter(filter) {
        activeFilter = filter;
        // Update button styles
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('filter-active', 'bg-blue-500', 'text-white', 'border-blue-500');
            btn.classList.add('bg-white', 'text-slate-600', 'border-slate-200');
        });
        const activeBtn = document.getElementById('filter-' + filter);
        if (activeBtn) {
            activeBtn.classList.add('filter-active', 'bg-blue-500', 'text-white', 'border-blue-500');
            activeBtn.classList.remove('bg-white', 'text-slate-600', 'border-slate-200');
        }
        filterKatalog();
    }

    function filterKatalog() {
        const keyword = document.getElementById('search-barang').value.toLowerCase().trim();
        const cards = document.querySelectorAll('#katalog-grid > div[data-nama]');
        let visibleCount = 0;

        cards.forEach(card => {
            const nama = card.getAttribute('data-nama').toLowerCase();
            const kode = card.getAttribute('data-kode').toLowerCase();
            const tersedia = card.getAttribute('data-tersedia') === '1';

            const matchKeyword = !keyword || nama.includes(keyword) || kode.includes(keyword);
            const matchFilter = activeFilter === 'semua' ||
                                (activeFilter === 'tersedia' && tersedia) ||
                                (activeFilter === 'na' && !tersedia);

            if (matchKeyword && matchFilter) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Tampilkan pesan jika tidak ada hasil
        const emptyMsg = document.getElementById('empty-search-msg');
        if (emptyMsg) emptyMsg.classList.toggle('hidden', visibleCount > 0);
    }

    function addToCart(barangId) {
        fetch('{{ route("siswa.keranjang.tambah") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ barang_id: barangId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message, timer: 1500, showConfirmButton: false });
                updateCartUI(); // Segera refresh dashboard untuk update tombol dan stok via API
            } else if (data.blocked) {
                // Siswa masih punya pinjaman aktif — tampilkan alert khusus premium
                Swal.fire({
                    title: '<span style="font-size:1.05rem;font-weight:800;color:#1e293b">🔒 Peminjaman Dikunci</span>',
                    html: `
                        <div style="font-size:0.83rem;color:#475569;line-height:1.65;text-align:center">
                            <p style="margin-bottom:14px">${data.message}</p>
                            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:11px 14px;margin-bottom:4px">
                                <p style="font-size:0.77rem;color:#991b1b;font-weight:600">
                                    ⚠️ Peraturan sekolah: setiap siswa hanya boleh memiliki
                                    <strong>1 sesi peminjaman aktif</strong> pada satu waktu.
                                </p>
                            </div>
                        </div>`,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonText: '📋 Lihat Riwayat & Kembalikan',
                    cancelButtonText: 'Tutup',
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#64748b',
                    reverseButtons: true,
                    width: '420px',
                }).then(result => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("siswa.riwayat") }}';
                    }
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({ icon: 'error', title: 'Oops...', text: 'Terjadi kesalahan saat menghubungi server.' });
        });
    }

    // ─── Real-time Polling & Pull-to-Refresh ────────────────────────────
    const POLL_INTERVAL = 5000;

    function updateCartUI() {
         return fetch('{{ route("siswa.api.data") }}', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            // Update stats
            const statsCards = document.querySelectorAll('.bg-amber-50 .text-xl, .bg-blue-50 .text-xl');
            if (statsCards[0]) statsCards[0].textContent = data.stats.sedang_dipinjam;
            if (statsCards[1]) statsCards[1].textContent = data.stats.total_pinjam;

            // Update cart counter
            const counter = document.getElementById('cart-counter');
            if (counter) counter.textContent = data.cart_count;

            // Update katalog barang
            const grid = document.getElementById('katalog-grid');
            if (grid && data.barangs) {
                grid.innerHTML = data.barangs.map(b => {
                    const butuhMaintenance = b.batas_pemakaian > 0 && b.jumlah_dipakai >= b.batas_pemakaian;
                    const tersedia = b.stok > 0 && b.kondisi === 'baik' && !butuhMaintenance;
                    const stokColor = b.stok > 2 ? 'emerald' : (b.stok > 0 ? 'amber' : 'red');
                    const qtyInCart = data.cart[b.id] || 0;
                    const inCart = qtyInCart > 0;
                    let actionBtn = '';
                    if (tersedia) {
                        if (inCart) {
                            actionBtn = `<button onclick="addToCart(${b.id})" class="w-full bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg py-2 text-[10px] sm:text-xs font-semibold transition-colors">Di Keranjang (${qtyInCart}) <span class="text-xs font-normal">+</span></button>`;
                        } else {
                            actionBtn = `<button onclick="addToCart(${b.id})" class="btn-primary w-full justify-center text-[10px] sm:text-xs py-2 px-1">+ Pinjam</button>`;
                        }
                    } else {
                        const btnText = butuhMaintenance ? 'Maintenance' : 'N/A';
                        actionBtn = `<button disabled class="w-full bg-slate-100 text-slate-400 rounded-lg py-2 text-[10px] sm:text-xs font-semibold cursor-not-allowed">${btnText}</button>`;
                    }

                    return `
                    <div class="card p-3 sm:p-4 hover:shadow-md transition-all duration-200 group flex flex-col"
                         data-nama="${b.nama_barang}"
                         data-kode="${b.kode_barang}"
                         data-tersedia="${tersedia ? '1' : '0'}">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center mb-3 ${tersedia ? 'bg-blue-100' : 'bg-slate-100'}">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 ${tersedia ? 'text-blue-600' : 'text-slate-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-[9px] sm:text-xs font-mono text-slate-400 mb-0.5">${b.kode_barang}</p>
                            <h3 class="font-semibold text-slate-800 text-xs sm:text-sm leading-tight mb-2 min-h-[2rem]">${b.nama_barang}</h3>
                        </div>
                        <div class="mt-auto">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-1">
                                    <div class="w-1.5 h-1.5 rounded-full bg-${stokColor}-500"></div>
                                    <span class="text-[10px] font-medium text-${stokColor}-600">S: ${b.stok}</span>
                                </div>
                                <span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase">${b.kondisi}</span>
                            </div>
                            ${actionBtn}
                        </div>
                    </div>`;
                }).join('');
            }
            filterKatalog(); // Re-apply search/filter after grid update
        })
        .catch(err => console.warn('Polling error:', err));
    }

    function refreshDashboard() {
       updateCartUI();
    }

    setInterval(refreshDashboard, POLL_INTERVAL);

    // --- Pull to Refresh Logic (Aman untuk Navbar) ---
    let touchStartY = 0;
    let isRefreshing = false;
    const pullRefreshEl = document.getElementById('pull-refresh');

    function triggerRefresh() {
        if (isRefreshing) return;
        isRefreshing = true;
        if(pullRefreshEl) pullRefreshEl.classList.remove('hidden');
        
        refreshDashboard().finally(() => {
            setTimeout(() => {
                if(pullRefreshEl) pullRefreshEl.classList.add('hidden');
                isRefreshing = false;
            }, 500);
        });
    }

    window.addEventListener('touchstart', e => {
        if (window.scrollY <= 0) {
            touchStartY = e.touches[0].clientY;
        } else {
            touchStartY = 0;
        }
    }, {passive: true});

    window.addEventListener('touchmove', e => {
        if (touchStartY > 0 && !isRefreshing && window.scrollY <= 0) {
            const currentY = e.touches[0].clientY;
            if (currentY > touchStartY + 30) {
                if(pullRefreshEl) pullRefreshEl.classList.remove('hidden');
            }
        }
    }, {passive: true});

    window.addEventListener('touchend', e => {
        if (touchStartY > 0 && !isRefreshing) {
            const touchEndY = e.changedTouches[0].clientY;
            if (touchEndY > touchStartY + 60) {
                triggerRefresh();
            } else {
                if(pullRefreshEl) pullRefreshEl.classList.add('hidden');
            }
            touchStartY = 0;
        }
    });

    window.addEventListener('wheel', e => {
        if (window.scrollY <= 0 && e.deltaY < -20 && !isRefreshing) {
            triggerRefresh();
        }
    }, {passive: true});
</script>
@endpush
@endsection
