<x-mail::message>
{{-- Header Logo/Brand --}}
<div style="text-align:center; margin-bottom: 8px;">
    <div style="display:inline-block; background:#dc2626; color:#fff; font-weight:800; font-size:1.3rem; padding:10px 24px; border-radius:10px; letter-spacing:1px;">
        ⚙️ SIPBELA
    </div>
    <p style="color:#64748b; font-size:0.78rem; margin-top:6px;">Sistem Peminjaman Alat Bengkel · SMK Ma'arif Talang</p>
</div>

---

# 🚨 Peringatan Keterlambatan Pengembalian Alat

Halo, **{{ $namaSiswa }}**!

Kami memberitahukan bahwa Anda **terlambat mengembalikan** alat bengkel yang Anda pinjam. Harap segera hubungi admin bengkel dan kembalikan alat tersebut.

<x-mail::panel>
**Detail Keterlambatan:**

| | |
|---|---|
| 🔧 **Nama Alat** | {{ $namaBarang }} |
| 📦 **Jumlah** | {{ $jumlah }} unit |
| 📅 **Batas Pengembalian** | {{ $batasKembali }} |
| 🚨 **Keterlambatan** | **{{ $hariTerlambat }} hari** |
| 💰 **Status Denda** | Berlaku jika alat hilang/rusak |
</x-mail::panel>

## Tindakan yang Harus Segera Dilakukan:

**1.** Kembalikan alat ke **admin/toolman bengkel SECEPATNYA**.

**2.** Klik tombol **"Kembalikan"** di aplikasi SIPBELA untuk mengajukan pengembalian.

**3.** Pastikan alat dalam kondisi baik. Jika ada kerusakan/kehilangan, harap laporkan langsung ke admin.

<x-mail::button :url="config('app.url') . '/siswa/riwayat'" color="error">
    🚨 Kembalikan Alat Sekarang
</x-mail::button>

---

> **⚠️ Ketentuan Denda:**
> Apabila alat yang dipinjam **hilang** atau **rusak berat**, peminjam wajib membayar denda sebesar **harga alat tersebut** kepada admin bengkel.

---

> Jika Anda sudah mengembalikan alat namun masih menerima email ini, kemungkinan admin belum mengkonfirmasi pengembalian. Harap hubungi admin bengkel.

---

<p style="color:#94a3b8; font-size:0.75rem; text-align:center;">
    Email ini dikirim otomatis oleh sistem SIPBELA · SMK Ma'arif Talang<br>
    Jangan balas email ini · © {{ date('Y') }} SIPBELA
</p>
</x-mail::message>
