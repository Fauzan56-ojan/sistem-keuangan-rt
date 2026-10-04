<x-mail::message>
# Yth. {{ $name }},

Pembayaran iuran bulan **{{ $bulan }} {{ $tahun }}** sebesar **Rp {{ $nominal }}** telah **berhasil**.

<x-mail::panel>
**Nama:** {{ $name }}<br>
**Periode:** {{ $bulan }} {{ $tahun }}<br>
**Nominal:** Rp {{ $nominal }}<br>
**Metode Pembayaran:** {{ $metode }}<br>
**Tanggal Bayar:** {{ $tanggalBayar }}
</x-mail::panel>

Untuk melihat rincian iuran Anda, silakan klik tombol berikut (wajib login):

<x-mail::button :url="$iuranUrl">
Lihat Rincian Iuran
</x-mail::button>

Demikian pemberitahuan ini. Apabila ada pertanyaan, silakan hubungi pengurus RT.

Salam hangat,<br>
Bendahara {{ $appName }}
</x-mail::message>
