<x-app-layout>
    <div class="min-h-screen bg-gray-50/50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-10 border-b border-gray-100 pb-5">
                <div class="flex items-start gap-4">

                    <a href="{{ url('/settings') }}" 
                        class="inline-flex items-center justify-center w-10 h-10 bg-white hover:bg-gray-50 text-gray-600 hover:text-gray-900 rounded-xl border border-gray-200 shadow-sm transition-all group shrink-0 mt-0.5" 
                        title="Kembali">

                        <span class="material-symbols-outlined text-xl group-hover:-translate-x-0.5 transition-transform">
                            arrow_back
                        </span>
                    </a>

                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                            Histori Pembayaran Lama
                        </h1>

                        <p class="mt-1 text-sm text-gray-500">
                            Manajemen data pembayaran lawas untuk warga aktif maupun nonaktif.
                        </p>
                    </div>

                </div>

                <div class="shrink-0">
                    <button
                        onclick="document.getElementById('modalNonaktif').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 h-11 px-5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow-sm hover:shadow transition-all duration-200 whitespace-nowrap">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Warga Nonaktif
                    </button>
                </div>
            </div>

            <div class="space-y-8">
                
                <!-- SECTION 1: WARGA AKTIF -->
                <details class="group/section bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" {{ request('search_aktif') ? 'open' : '' }}>
                    <summary class="flex items-center justify-between p-6 cursor-pointer select-none list-none bg-white hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg group-open/section:bg-emerald-600 group-open/section:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            <span class="font-bold text-lg text-gray-800">Warga Aktif</span>
                        </div>
                        <div class="text-gray-400 group-hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5 transform group-open/section:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </summary>

                    <div class="px-6 pb-6 border-t border-gray-100 bg-white">
                        <!-- Search Form Aktif -->
                        <div class="py-4 flex justify-end">
                            <form method="GET" class="relative w-full md:w-80 group/search">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within/search:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search_aktif" value="{{ request('search_aktif') }}" placeholder="Cari warga aktif..."
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all shadow-sm text-sm">
                            </form>
                        </div>

                        <!-- Table Aktif -->
                        <div class="overflow-x-auto border border-gray-100 rounded-xl">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/70 border-b border-gray-100">
                                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Warga</th>
                                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor Rumah</th>
                                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($aktif as $user)                                    
                                        @php
                                            $words = explode(' ', $user->name);

                                            $initials = strtoupper(
                                                substr($words[0], 0, 1) .
                                                (isset($words[1]) ? substr($words[1], 0, 1) : '')
                                            );
                                        @endphp
                                        <tr class="hover:bg-gray-50/50 transition-colors group/row">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-4">
                                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-xs font-bold shadow-sm group-hover/row:scale-105 transition-transform">
                                                        {{ $initials }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                                        <div class="text-xs text-emerald-600 font-medium">Status: Aktif</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                    Blok {{ $user->nomor_rumah ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('settings.histori.detail', $user->id) }}"
                                                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-lg text-xs font-bold hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all duration-200 shadow-sm">
                                                    <span>Detail Histori</span>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                                <p class="text-sm font-medium">Data warga aktif tidak ditemukan</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </details>

                <!-- SECTION 2: WARGA TIDAK AKTIF -->
                <details class="group/section bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" {{ request('search_nonaktif') ? 'open' : '' }}>
                    <summary class="flex items-center justify-between p-6 cursor-pointer select-none list-none bg-white hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="p-2 bg-gray-100 text-gray-500 rounded-lg group-open/section:bg-gray-600 group-open/section:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
                                </svg>
                            </span>
                            <span class="font-bold text-lg text-gray-800">Warga Tidak Aktif</span>
                        </div>
                        <div class="text-gray-400 group-hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5 transform group-open/section:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </summary>

                    <div class="px-6 pb-6 border-t border-gray-100 bg-white">
                        <!-- Search Form Non-Aktif -->
                        <div class="py-4 flex justify-end">
                            <form method="GET" class="relative w-full md:w-80 group/search">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within/search:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search_nonaktif" value="{{ request('search_nonaktif') }}" placeholder="Cari warga nonaktif..."
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all shadow-sm text-sm">
                            </form>
                        </div>

                        <!-- Table Non-Aktif -->
                        <div class="overflow-x-auto border border-gray-100 rounded-xl">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/70 border-b border-gray-100">
                                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Warga</th>
                                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor Rumah</th>
                                        <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($nonaktif as $user)
                                        @php
                                            $words = explode(' ', $user->name);
                                            $initials = strtoupper(substr($words[0],0,1) . (isset($words[1]) ? substr($words[1],0,1) : ''));
                                        @endphp <!-- Perbaikan di baris ini -->
                                        <tr class="hover:bg-gray-50/50 transition-colors group/row">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-4">
                                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-white text-xs font-bold shadow-sm group-hover/row:scale-105 transition-transform">
                                                        {{ $initials }}
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                                        <div class="text-xs text-gray-400 font-medium">Status: Nonaktif</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                    Blok {{ $user->nomor_rumah ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('settings.histori.detail', $user->id) }}"
                                                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-lg text-xs font-bold hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all duration-200 shadow-sm">
                                                    <span>Detail Histori</span>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                                <p class="text-sm font-medium">Data warga nonaktif tidak ditemukan</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </details>

            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH WARGA NONAKTIF -->
    <div id="modalNonaktif" class="hidden fixed inset-0 bg-gray-900/60 z-50 flex items-center justify-center p-4 transition-all animate-fade-in">
        <form method="POST" action="{{ route('settings.histori.nonaktif.store') }}" class="bg-white rounded-2xl w-full max-w-lg shadow-xl border border-gray-100 overflow-hidden transform transition-all">
            @csrf
            
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <span class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </span>
                    Tambah Warga Nonaktif
                </h2>
                <button type="button" onclick="document.getElementById('modalNonaktif').classList.add('hidden')"
                    class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Warga</label>
                    <input type="text" name="name" required
                        class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white shadow-sm transition-all"
                        placeholder="Masukkan nama lengkap">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Rumah</label>
                    <input type="text" name="nomor_rumah" required
                        class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white shadow-sm transition-all"
                        placeholder="Contoh: A-12">
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalNonaktif').classList.add('hidden')"
                    class="px-4 py-2.5 border border-gray-200 hover:bg-gray-100 rounded-xl text-xs font-bold text-gray-700 transition-colors shadow-sm">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-colors shadow-sm">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</x-app-layout>