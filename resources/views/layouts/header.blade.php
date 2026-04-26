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
        <div class="flex items-center gap-2 text-xs bg-white px-3 py-1.5 rounded-full border">
            <span class="material-symbols-outlined text-sm text-emerald-500">calendar_today</span>
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>

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
        <button class="relative p-2 rounded-full bg-white border hover:bg-emerald-50 transition">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>

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