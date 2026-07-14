@extends('layouts.app')
@section('title', 'Akses Ditolak — Masih Ada Pinjaman Aktif')
@section('page-title', 'Pinjam Alat')
@section('page-subtitle', 'Checkout peminjaman alat bengkel')

@push('styles')
<style>
    /* ── Keyframes ──────────────────────────────────────────────────────── */
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-10px); }
    }
    @keyframes pulse-ring {
        0%   { transform: scale(0.9); opacity: 0.6; }
        70%  { transform: scale(1.15); opacity: 0; }
        100% { transform: scale(0.9); opacity: 0; }
    }
    @keyframes slide-up {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes shimmer {
        0%   { background-position: -400px 0; }
        100% { background-position: 400px 0; }
    }
    @keyframes count-bounce {
        0%,100% { transform: scale(1); }
        50%      { transform: scale(1.15); }
    }

    /* ── Wrapper ────────────────────────────────────────────────────────── */
    .blocked-wrap {
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; min-height: 72vh;
        animation: slide-up 0.45s cubic-bezier(.22,.68,0,1.2) both;
    }

    /* ── Icon Badge ─────────────────────────────────────────────────────── */
    .blocked-icon-ring {
        position: relative; width: 100px; height: 100px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 2rem;
    }
    .blocked-icon-ring::before {
        content: '';
        position: absolute; inset: -6px;
        border-radius: 50%;
        background: rgba(239,68,68,0.12);
        animation: pulse-ring 2s ease-out infinite;
    }
    .blocked-icon-bg {
        width: 90px; height: 90px; border-radius: 50%;
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        border: 2px solid #fecaca;
        display: flex; align-items: center; justify-content: center;
        animation: float 3.5s ease-in-out infinite;
        box-shadow: 0 8px 32px rgba(239,68,68,0.15);
    }
    .blocked-icon-bg svg { width: 42px; height: 42px; color: #ef4444; }

    /* ── Title & Desc ──────────────────────────────────────────────────── */
    .blocked-title {
        font-size: 1.5rem; font-weight: 800;
        color: #1e293b; text-align: center; margin-bottom: 0.5rem;
    }
    .blocked-desc {
        color: #64748b; text-align: center; font-size: 0.9rem;
        max-width: 400px; line-height: 1.7; margin-bottom: 2rem;
    }

    /* ── Active Loans Card ──────────────────────────────────────────────── */
    .loan-card {
        background: #fff;
        border-radius: 1.1rem;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        width: 100%; max-width: 520px;
        overflow: hidden;
        margin-bottom: 1.75rem;
    }
    .loan-card-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        padding: 1rem 1.25rem;
        display: flex; align-items: center; justify-content: space-between;
    }
    .loan-count-badge {
        display: inline-flex; align-items: center; justify-content: center;
        background: #ef4444; color: #fff;
        width: 1.75rem; height: 1.75rem; border-radius: 50%;
        font-size: 0.8rem; font-weight: 800;
        animation: count-bounce 1.5s ease-in-out infinite;
        box-shadow: 0 0 0 4px rgba(239,68,68,0.25);
    }
    .loan-item {
        display: flex; align-items: center; gap: 1rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f8fafc;
        transition: background 0.15s;
    }
    .loan-item:last-child { border-bottom: none; }
    .loan-item:hover { background: #fafafa; }
    .loan-item-icon {
        width: 42px; height: 42px; border-radius: 0.75rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .loan-item-icon.dipinjam  { background: #fef3c7; }
    .loan-item-icon.menunggu  { background: #dbeafe; }
    .loan-item-icon svg { width: 20px; height: 20px; }
    .loan-item-name { font-weight: 700; color: #1e293b; font-size: 0.875rem; }
    .loan-item-meta { font-size: 0.72rem; color: #94a3b8; margin-top: 0.15rem; }
    .status-pill {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.2rem 0.65rem; border-radius: 999px;
        font-size: 0.7rem; font-weight: 700; flex-shrink: 0;
    }
    .status-dipinjam  { background: #fef3c7; color: #92400e; }
    .status-menunggu  { background: #dbeafe; color: #1e40af; }

    /* ── Stepper Info ───────────────────────────────────────────────────── */
    .steps-wrap {
        display: flex; flex-direction: column; gap: 0.75rem;
        width: 100%; max-width: 520px; margin-bottom: 2rem;
    }
    .step-row {
        display: flex; align-items: flex-start; gap: 0.875rem;
        padding: 0.75rem 1rem;
        background: #f8fafc; border-radius: 0.75rem;
        border: 1px solid #f1f5f9;
    }
    .step-num {
        width: 1.75rem; height: 1.75rem; border-radius: 50%;
        background: #2563eb; color: #fff;
        font-size: 0.72rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; margin-top: 0.1rem;
    }
    .step-text strong { font-size: 0.82rem; color: #1e293b; }
    .step-text p { font-size: 0.75rem; color: #64748b; margin-top: 0.1rem; }

    /* ── Action Buttons ─────────────────────────────────────────────────── */
    .action-group {
        display: flex; gap: 0.875rem; flex-wrap: wrap; justify-content: center;
    }
    .btn-riwayat {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #fff; padding: 0.65rem 1.5rem;
        border-radius: 0.75rem; font-size: 0.875rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: 0.5rem;
        text-decoration: none; border: none; cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(37,99,235,0.35);
    }
    .btn-riwayat:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,99,235,0.4); }
    .btn-back {
        padding: 0.65rem 1.5rem;
        border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 0.5rem;
        text-decoration: none; border: 1.5px solid #e2e8f0;
        color: #64748b; background: #fff;
        transition: all 0.2s;
    }
    .btn-back:hover { background: #f8fafc; border-color: #cbd5e1; color: #334155; }

    /* ── Warning Banner ─────────────────────────────────────────────────── */
    .warn-banner {
        display: flex; align-items: center; gap: 0.75rem;
        background: linear-gradient(135deg, #fff7ed 0%, #fef3c7 100%);
        border: 1px solid #fed7aa; border-radius: 0.875rem;
        padding: 0.875rem 1.1rem;
        width: 100%; max-width: 520px;
        margin-bottom: 1.75rem;
    }
    .warn-banner p { font-size: 0.8rem; color: #92400e; line-height: 1.5; }
    .warn-banner strong { color: #78350f; }
</style>
@endpush

@section('content')
<div class="blocked-wrap">

    {{-- ── Floating Lock Icon ─────────────────────────────────────────── --}}
    <div class="blocked-icon-ring">
        <div class="blocked-icon-bg">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
    </div>

    {{-- ── Title ──────────────────────────────────────────────────────── --}}
    <h1 class="blocked-title">Peminjaman Dikunci 🔒</h1>
    <p class="blocked-desc">
        Anda belum bisa meminjam alat baru karena masih ada pinjaman yang
        <strong>belum dikembalikan</strong>. Selesaikan pinjaman aktif terlebih dahulu.
    </p>

    {{-- ── Warning Banner ─────────────────────────────────────────────── --}}
    <div class="warn-banner">
        <svg class="w-8 h-8 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <p>
            Peraturan sekolah: <strong>setiap siswa hanya boleh memiliki 1 sesi peminjaman aktif</strong>
            pada satu waktu. Kembalikan semua alat di bawah ini terlebih dahulu, kemudian Anda dapat
            meminjam lagi.
        </p>
    </div>

    {{-- ── Active Loans List ───────────────────────────────────────────── --}}
    <div class="loan-card">
        <div class="loan-card-header">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="text-white font-bold text-sm">Pinjaman Aktif Anda</span>
            </div>
            <span class="loan-count-badge">{{ $pinjamanAktif->count() }}</span>
        </div>

        @foreach($pinjamanAktif as $p)
        <div class="loan-item">
            {{-- Icon status --}}
            <div class="loan-item-icon {{ $p->status === 'dipinjam' ? 'dipinjam' : 'menunggu' }}">
                @if($p->status === 'dipinjam')
                    <svg fill="none" stroke="#f59e0b" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    </svg>
                @else
                    <svg fill="none" stroke="#3b82f6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <p class="loan-item-name truncate">{{ $p->barang->nama_barang }}</p>
                <p class="loan-item-meta">
                    Dipinjam: {{ $p->tanggal_pinjam->translatedFormat('d M Y') }}
                    @if($p->batas_kembali)
                    &nbsp;·&nbsp; Batas: {{ $p->batas_kembali->translatedFormat('d M Y') }}
                    @endif
                </p>
                @if($p->isTerlambat())
                <p class="text-[11px] font-bold text-red-500 mt-0.5">
                    ⚠️ Terlambat {{ $p->hariTerlambatSekarang() }} hari!
                </p>
                @endif
            </div>

            {{-- Status badge --}}
            <span class="status-pill {{ $p->status === 'dipinjam' ? 'status-dipinjam' : 'status-menunggu' }}">
                @if($p->status === 'dipinjam')
                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span> Dipinjam
                @else
                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span> Menunggu
                @endif
            </span>
        </div>
        @endforeach
    </div>

    {{-- ── Steps to Unlock ────────────────────────────────────────────── --}}
    <div class="steps-wrap">
        <div class="step-row">
            <div class="step-num">1</div>
            <div class="step-text">
                <strong>Buka halaman Riwayat</strong>
                <p>Lihat semua pinjaman aktif Anda dan klik tombol "Kembalikan".</p>
            </div>
        </div>
        <div class="step-row">
            <div class="step-num">2</div>
            <div class="step-text">
                <strong>Serahkan fisik alat ke Admin</strong>
                <p>Bawa alat ke ruang bengkel dan serahkan ke admin/toolman untuk dikonfirmasi.</p>
            </div>
        </div>
        <div class="step-row">
            <div class="step-num">3</div>
            <div class="step-text">
                <strong>Pinjaman dikonfirmasi ✅</strong>
                <p>Setelah admin mengkonfirmasi pengembalian, Anda bebas meminjam lagi.</p>
            </div>
        </div>
    </div>

    {{-- ── Actions ─────────────────────────────────────────────────────── --}}
    <div class="action-group">
        <a href="{{ route('siswa.riwayat') }}" class="btn-riwayat">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Lihat Riwayat & Kembalikan
        </a>
        <a href="{{ route('siswa.dashboard') }}" class="btn-back">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Katalog
        </a>
    </div>

</div>
@endsection
