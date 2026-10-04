<x-mail::message>
# Halo {{ $name }},

Akun Anda telah berhasil dibuat oleh admin **{{ $appName }}**. Anda dapat menggunakan akun tersebut untuk masuk ke sistem.

## Informasi Akun

<x-mail::panel>
**Nama:** {{ $name }}<br>
**Email:** {{ $email }}<br>
**Password:** {{ $password }}
</x-mail::panel>

Untuk masuk, silakan gunakan email dan password di atas melalui halaman login berikut:

<x-mail::button :url="$loginUrl">
Masuk ke Sistem
</x-mail::button>

Demikian pemberitahuan ini. Apabila ada pertanyaan, silakan hubungi pengurus RT.

Salam hangat,<br>
Admin {{ $appName }}
</x-mail::message>
