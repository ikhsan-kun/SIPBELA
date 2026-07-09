<x-mail::message>
{{-- Header Logo/Brand --}}
<div style="text-align:center; margin-bottom: 8px;">
    <div style="display:inline-block; background:#2563eb; color:#fff; font-weight:800; font-size:1.3rem; padding:10px 24px; border-radius:10px; letter-spacing:1px;">
        ⚙️ SIPBELA
    </div>
    <p style="color:#64748b; font-size:0.78rem; margin-top:6px;">Sistem Peminjaman Alat Bengkel · SMK Ma'arif Talang</p>
</div>

---

# ⚠️ Pengingat Batas Pengembalian Alat

Halo, **{{ $namaSiswa }}**!

Ini adalah pengingat bahwa alat yang Anda pinjam akan **habis masa peminjamannya besok**. Harap segera dikembalikan ke admin bengkel agar tidak terkena denda.

<x-mail::panel>
**Detail Peminjaman:**

| | |
|---|---|
| 🔧 **Nama Alat** | {{ $namaBarang }} |
| 📦 **Jumlah** | {{ $jumlah }} unit |
| 📅 **Batas Pengembalian** | **{{ $batasKembali }}** |
| ⏰ **Status** | ⚠️ Jatuh tempo **BESOK** |
</x-mail::panel>

## Yang Perlu Anda Lakukan:

**1.** Siapkan alat yang dipinjam dalam kondisi baik dan lengkap.

**2.** Kembalikan alat ke **admin/toolman bengkel** paling lambat besok.

**3.** Setelah menyerahkan alat, klik tombol **"Kembalikan"** di aplikasi SIPBELA agar admin bisa mengkonfirmasi.

<x-mail::button :url="config('app.url') . '/siswa/riwayat'" color="primary">
    📋 Lihat Riwayat Peminjaman Saya
</x-mail::button>

---

> **⚠️ Ingat Peraturan Peminjaman:**
> - Alat wajib dikembalikan dalam kondisi semula
> - Alat **tidak boleh** dibawa keluar sekolah tanpa izin toolman
> - Apabila alat **hilang**, peminjam wajib membayar denda sebesar **harga alat** kepada admin

---

<p style="color:#94a3b8; font-size:0.75rem; text-align:center;">
    Email ini dikirim otomatis oleh sistem SIPBELA · SMK Ma'arif Talang<br>
    Jangan balas email ini · © {{ date('Y') }} SIPBELA
</p>
</x-mail::message>
