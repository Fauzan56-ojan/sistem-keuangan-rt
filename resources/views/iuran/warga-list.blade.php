<x-app-layout>
    <div class="min-h-screen bg-gray-50/50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Iuran Warga</h1>
                    <p class="mt-1 text-sm text-gray-500">Sistem manajemen data iuran dan monitoring pembayaran warga.</p>
                </div>

                <form method="GET" class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama atau nomor rumah..."
                        class="block w-full md:w-80 pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all shadow-sm text-sm">
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="p-3 bg-emerald-50 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Warga</p>
                        <h3 class="text-2xl font-bold text-gray-900 leading-none">{{ count($users) }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Data Ditemukan</p>
                        <h3 class="text-2xl font-bold text-gray-900 leading-none">{{ count($users) }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="p-3 bg-green-50 rounded-lg">
                        <span class="material-symbols-outlined text-green-600">check_circle</span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Sudah Bayar ({{ now()->translatedFormat('M') }})</p>
                        <h3 class="text-2xl font-bold text-green-600">{{ $sudahBayar }}</h3>
                    </div>
                </div>

                <a href="{{ route('belum.bayar') }}"
                class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:bg-red-50 transition">

                    <div class="p-3 bg-red-50 rounded-lg">
                        <span class="material-symbols-outlined text-red-600">warning</span>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase">Belum Bayar ({{ now()->translatedFormat('M') }})</p>
                        <h3 class="text-2xl font-bold text-red-600">{{ $belumBayar }}</h3>
                    </div>
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Warga</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor Rumah</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($users as $user)
                                @php
                                    $words = explode(' ', $user->name);
                                    $initials = strtoupper(substr($words[0],0,1) . (isset($words[1]) ? substr($words[1],0,1) : ''));
                                @endphp
                                <tr class="hover:bg-gray-50/80 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-xs font-bold shadow-sm group-hover:scale-105 transition-transform">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-500 italic">Terdaftar</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            Blok {{ $user->nomor_rumah }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ url('/warga/' . $user->id . '/iuran') }}"
                                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-lg text-xs font-bold hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all duration-200 shadow-sm">
                                            <span>Detail Iuran</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-base font-medium">Data tidak ditemukan</p>
                                            <p class="text-sm">Coba kata kunci pencarian yang lain.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>