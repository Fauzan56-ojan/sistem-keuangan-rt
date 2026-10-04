<x-mail::message>
# Yth. {{ $name }},

Kami informasikan bahwa Anda memiliki tunggakan pembayaran iuran sebanyak **{{ $jumlahBulan }} bulan** dengan total tagihan **Rp {{ $total }}**.

Mohon untuk segera menyelesaikannya agar catatan keuangan RT tetap rapi.

<x-mail::button :url="$tunggakanUrl">
Lihat Rincian Tunggakan
</x-mail::button>

Apabila Anda sudah melakukan pembayaran, silakan abaikan email ini. Bila ada pertanyaan, silakan hubungi pengurus RT.

Salam hangat,<br>
Pengurus {{ $appName }}
</x-mail::message>
