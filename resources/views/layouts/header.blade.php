@php
    $notif = null;

    if (Auth::user()->role === 'warga') {
        $notif = \App\Services\NotificationService::getNotifData(Auth::user());
    }
@endphp
<header class="glass-header fixed top-0 left-64 right-0 z-40 flex items-center justify-between px-7 py-3.5">

    <!-- kiri -->
    <div class="flex items-center gap-4">

        {{-- <div>
            <p class="text-xs text-emerald-600">Selamat datang,</p>
            <h1 class="text-lg font-bold text-slate-800">
                {{ Auth::user()->name ?? 'User' }}
            </h1>
        </div> --}}

        <!-- divider -->
        {{-- <div class="h-6 w-px bg-slate-200"></div> --}}

        <!-- tanggal -->
        <!-- <div class="flex items-center gap-2 text-xs bg-white px-3 py-1.5 rounded-full border">
            <span class="material-symbols-outlined text-sm text-emerald-500">calendar_today</span>
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
        </div> -->

    </div>

    <!-- kanan -->
    <div class="flex items-center gap-3">

        <!-- search -->
        {{-- <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-2 text-slate-400">search</span>
            <input type="text"
                placeholder="Cari transaksi, warga..."
                class="pl-9 pr-4 py-2 text-sm bg-white border rounded-full w-60 focus:ring-2 focus:ring-emerald-200">
        </div> --}}

        <!-- notif -->
        @if(Auth::user()->role === 'warga')

        <div class="relative">

            <!-- tombol notif -->
            <button
                onclick="document.getElementById('notifDropdown').classList.toggle('hidden')"
                class="relative p-2 rounded-full bg-white border hover:bg-emerald-50 transition">

                <span class="material-symbols-outlined">
                    notifications
                </span>

                @if($notif['count'] > 0)
                    <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center text-[10px] font-bold text-white bg-red-500 rounded-full">
                        {{ $notif['count'] }}
                    </span>
                @endif

            </button>

            <!-- dropdown -->
            <div id="notifDropdown"
                class="hidden absolute right-0 mt-3 w-72 bg-white border rounded-2xl shadow-xl overflow-hidden z-50">

                <div class="px-4 py-3 border-b font-semibold text-sm text-slate-700">
                    Notifikasi
                </div>

                @if($notif['count'] == 0)

                    <div class="px-4 py-6 text-sm text-slate-500 text-center">
                        Tidak ada notifikasi
                    </div>

                @endif

                @if($notif['password'])

                    <a href="{{ route('settings.profile') }}"
                        class="block px-4 py-3 hover:bg-slate-50 border-b">

                        <div class="font-medium text-sm text-slate-800">
                            Ganti Password
                        </div>

                        <div class="text-xs text-slate-500 mt-1">
                            Segera ganti password akun Anda
                        </div>

                    </a>

                @endif

                @if($notif['tunggakan'])

                    <a href="/tunggakan/{{ Auth::user()->id }}"
                        class="block px-4 py-3 hover:bg-slate-50">

                        <div class="font-medium text-sm text-slate-800">
                            Tunggakan Iuran
                        </div>

                        <div class="text-xs text-slate-500 mt-1">
                            Anda memiliki tunggakan pembayaran
                        </div>

                    </a>

                @endif

            </div>

        </div>

        @endif

        <!-- profile -->
                @php
            $name = Auth::user()->name;

            // ambil inisial (maks 2 huruf)
            $words = explode(' ', $name);
            $initials = strtoupper(
                substr($words[0], 0, 1) .
                (isset($words[1]) ? substr($words[1], 0, 1) : '')
            );
        @endphp

        <div class="flex items-center gap-3 bg-white px-3 py-2 rounded-full border shadow-sm">

            <!-- Avatar Inisial -->
            <div class="w-9 h-9 flex items-center justify-center rounded-full bg-emerald-500 text-white font-semibold text-sm">
                {{ $initials }}
            </div>

            <!-- Nama + Role -->
            <div class="leading-tight">
                <div class="text-sm font-semibold text-gray-800">
                    {{ $name }}
                </div>
                <div class="text-xs text-gray-500">
                    {{ Auth::user()->role ?? 'User' }}
                </div>
            </div>

        </div>

    </div>
</header>