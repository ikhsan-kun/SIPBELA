@extends('perpustakaan.layouts.app')

@section('title', 'Katalog Buku')
@section('page-title', 'Katalog Buku')
@section('page-subtitle', 'Temukan buku yang ingin kamu pinjam')

@section('content')
<!-- Filter -->
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari judul, penulis, penerbit..."
            class="flex-1 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <select name="kategori" class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $k)
                <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-perpus">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Cari
        </button>
    </form>
</div>

<!-- Grid Buku -->
<div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
    @forelse($bukus as $buku)
    <div class="card overflow-hidden hover:shadow-md transition-all duration-200 hover:-translate-y-1 flex flex-col">
        <!-- Book Header -->
        <div class="h-40 sm:h-48 flex items-center justify-center relative overflow-hidden bg-slate-100">
            @if($buku->gambar)
                <img src="{{ asset('storage/' . $buku->gambar) }}" alt="{{ $buku->judul }}" class="w-full h-full object-contain">
            @else
                <div class="w-full h-full bg-gradient-to-br from-green-600 to-green-800 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            @endif
            <!-- Stok badge -->
            <div class="absolute top-2 right-2">
                <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $buku->stok > 0 ? 'bg-green-400/90 text-white' : 'bg-red-500/90 text-white shadow-lg' }}">
                    {{ $buku->stok > 0 ? $buku->stok . ' tersedia' : 'Habis' }}
                </span>
            </div>
            @if($buku->kategori)
            <div class="absolute bottom-2 left-2">
                <span class="text-xs bg-slate-900/40 backdrop-blur-sm text-white px-2 py-0.5 rounded-full">{{ $buku->kategori }}</span>
            </div>
            @endif
        </div>

        <div class="p-3 sm:p-4 flex-1 flex flex-col">
            <h3 class="font-bold text-slate-800 text-xs sm:text-sm leading-snug mb-1 line-clamp-2 min-h-[2.5rem]">{{ $buku->judul }}</h3>
            <p class="text-[10px] sm:text-xs text-slate-500 mb-1 truncate">{{ $buku->penulis }}</p>
            @if($buku->penerbit)
                <p class="text-[9px] sm:text-xs text-slate-400 truncate">{{ $buku->penerbit }} @if($buku->tahun)· {{ $buku->tahun }}@endif</p>
            @endif
            
            <div class="mt-auto pt-3" id="action-container-{{ $buku->id }}">
                @php
                    $cart = session('cart_perpus', []);
                    $qtyInCart = $cart[$buku->id] ?? 0;
                    $inCart = $qtyInCart > 0;
                @endphp

                @if($buku->stok > 0)
                    @if($inCart)
                        <button onclick="addToCart({{ $buku->id }})" class="w-full bg-green-100 text-green-700 hover:bg-green-200 rounded-lg py-2 text-[10px] sm:text-xs font-semibold transition-colors">
                            Di Keranjang ({{ $qtyInCart }}) <span class="text-xs font-normal">+</span>
                        </button>
                    @else
                        <button onclick="addToCart({{ $buku->id }})"
                            class="btn-perpus w-full justify-center text-[10px] sm:text-xs py-2 px-1">
                            + Keranjang
                        </button>
                    @endif
                @else
                    <button disabled class="w-full text-center text-[10px] sm:text-xs py-2 bg-slate-100 text-slate-400 rounded-lg font-semibold cursor-not-allowed">
                        Stok Habis
                    </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-16 text-slate-400">
        <svg class="w-16 h-16 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
        Buku tidak ditemukan.
    </div>
    @endforelse
</div>

@if($bukus->hasPages())
<div class="mt-6">{{ $bukus->links() }}</div>
@endif

<!-- Floating Cart Badge -->
<a href="{{ route('perpustakaan.siswa.peminjaman.create') }}" id="floating-cart" class="fixed bottom-6 right-6 bg-green-600 text-white p-4 rounded-full shadow-lg hover:bg-green-700 transition-all flex items-center justify-center group z-50">
    <div class="relative">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        <span id="cart-counter" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white">{{ count(session('cart_perpus', [])) > 0 ? array_sum(session('cart_perpus', [])) : 0 }}</span>
    </div>
    <span class="ml-2 hidden group-hover:block whitespace-nowrap font-medium pr-1">Checkout</span>
</a>

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function addToCart(bukuId) {
        fetch('{{ route("perpustakaan.siswa.keranjang.tambah") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ buku_id: bukuId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update badge cart counter
                const badge = document.getElementById('cart-counter');
                if (badge) badge.innerText = data.cart_count;

                // Reload page to reflect UI state changes (or manually update innerHTML if preferred)
                window.location.reload();
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({ icon: 'error', title: 'Oops...', text: 'Terjadi kesalahan saat menghubungi server.' });
        });
    }
</script>
@endpush
@endsection
