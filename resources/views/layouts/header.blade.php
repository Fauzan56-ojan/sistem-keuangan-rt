@php
    $notif = null;

    if (Auth::user()->role === 'warga') {
        $notif = \App\Services\NotificationService::getNotifData(Auth::user());
    }
@endphp
<header id="headerContent" class="glass-header fixed top-0 left-56 right-0 z-40 flex items-center justify-between px-5 py-1.5">

    <!-- kiri -->
    <div class="flex items-center gap-4">

        <!-- toggle sidebar -->
        <button
    id="toggleSidebar"
    class="w-10 h-10 flex items-center justify-center rounded-md bg-white border hover:bg-emerald-50 transition">

    <span class="material-symbols-outlined text-[20px] leading-none">
        menu
    </span>

</button>

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

        <div x-data="{ open: false }" class="relative">

            <!-- tombol notif -->
            <button
                @click="open = !open"
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
            <div
                x-show="open"
                @click.outside="open = false"
                x-transition
                class="absolute right-0 mt-3 w-72 bg-white border rounded-2xl shadow-xl overflow-hidden z-50">

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
                        class="block px-4 py-3 hover:bg-slate-50 border-b transition">

                        <div class="flex items-start justify-between">

                            <div>
                                <div class="font-bold text-sm text-slate-800">
                                    Ganti Password
                                </div>

                                <div class="text-xs text-slate-500 mt-1">
                                    Segera ganti password akun Anda
                                </div>
                            </div>

                            <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center">
                                <span class="text-red-600 text-xs font-bold">!</span>
                            </div>

                        </div>

                    </a>

                @endif

                @if($notif['tunggakan'])

                    <a href="/tunggakan/{{ Auth::user()->id }}"
                        
                        class="block px-4 py-3 hover:bg-slate-50 transition">

                        <div class="flex items-start justify-between">

                            <div>
                                <div class="font-bold text-sm text-slate-800">
                                    Tunggakan Iuran
                                </div>

                                <div class="text-xs text-slate-500 mt-1">
                                    Anda memiliki tunggakan pembayaran
                                </div>
                            </div>

                            <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center">
                                <span class="text-red-600 text-xs font-bold">!</span>
                            </div>

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

        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-100 shadow-sm">

        <!-- Avatar Inisial (Agak Mengotak / Rounded-xl) -->
        <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shadow-md shadow-emerald-100">
            {{ $initials }}
        </div>

        <!-- Nama + Role -->
        <div class="text-left">
            <p class="text-sm font-bold text-slate-800 leading-none">
                {{ $name }}
            </p>
            <span class="text-[10px] font-bold text-slate-400 tracking-wider uppercase bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100 inline-block mt-1">
                {{ Auth::user()->role ?? 'User' }}
            </span>
        </div>

    </div>

    </div>
</header>