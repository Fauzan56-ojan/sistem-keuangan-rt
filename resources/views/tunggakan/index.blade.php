<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg">Tunggakan</h2>
    </x-slot>

    <div class="px-8 py-10 max-w-7xl mx-auto">

        <!-- HEADER -->
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-800">Tunggakan Iuran</h1>
            <p class="text-gray-500">Daftar warga yang memiliki tunggakan</p>
        </div>

        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-xl shadow-sm">
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-red-500 mr-3">error</span>
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @php
            $totalWarga = count($data);
            $totalNominal = collect($data)->sum('total');
        @endphp

        <!-- SUMMARY -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

            <!-- Total Warga -->
            <div class="bg-white p-6 rounded-xl shadow-sm border flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Total Warga Menunggak</p>
                    <p class="text-3xl font-bold text-red-600">
                        {{ $totalWarga }}
                        <span class="text-sm text-gray-400">orang</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 text-red-600 flex items-center justify-center rounded-full">
                    <span class="material-symbols-outlined">warning</span>
                </div>
            </div>

            <!-- Total Nominal -->
            <div class="bg-white p-6 rounded-xl shadow-sm border flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Total Tunggakan</p>
                    <p class="text-3xl font-bold text-gray-800">
                        Rp {{ number_format($totalNominal, 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 flex items-center justify-center rounded-full">
                    <span class="material-symbols-outlined">account_balance</span>
                </div>
            </div>

        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-4 text-left">Nama</th>
                            <th class="px-6 py-4 text-center">No. Rumah</th>
                            <th class="px-6 py-4">Jumlah Bulan</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>        

                    @forelse ($data as $item)
                    <tbody x-data="{ open: false }" class="divide-y">

                        @php
                            $words = explode(' ', $item['nama']);
                            $initials = strtoupper(substr($words[0],0,1) . (isset($words[1]) ? substr($words[1],0,1) : ''));

                            $bulan = $item['jumlah_bulan'];
                        @endphp

                        <tr class="hover:bg-gray-50 transition">

                            <!-- Nama -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">
                                            {{ $item['nama'] }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Nomor Rumah -->
                            <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            {{ $item['nomor_rumah'] ?? '-' }}
                                        </span>
                                    </td>

                            <!-- Bulan -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2
                                    {{ $bulan >= 4 ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                    
                                    @if($bulan >= 4)
                                        <span class="material-symbols-outlined text-[18px]">warning</span>
                                    @endif

                                    {{ $bulan }} bulan
                                </div>
                            </td>

                            <!-- Total -->
                            <td class="px-6 py-4 font-bold text-gray-800">
                                Rp {{ number_format($item['total'], 0, ',', '.') }}
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-4 text-center">
                                <button 
                                    @click="open = !open"
                                    class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-full text-xs font-semibold hover:bg-emerald-100 transition">
                                    <span x-show="!open">Detail</span>
                                    <span x-show="open">Tutup</span>
                                </button>
                            </td>

                        </tr>

                        <tr x-show="open" x-transition.duration.300ms x-cloak>
                            <td colspan="4" class="bg-gray-50 px-6 py-4">

                                <div class="rounded-xl border p-4">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Bulan</th>
                                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Tahun</th>
                                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Nominal</th>
                                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Status</th>

                                                @if(auth()->user()->role !== 'ketua_rt')
                                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">Aksi</th>
                                                @endif
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-100">
                                            @forelse($item['detail'] as $row)
                                            <tr class="hover:bg-red-50/30 transition-colors">
                                                
                                                <td class="px-6 py-5 font-semibold text-gray-900">
                                                    {{ \Carbon\Carbon::create()->month($row->periode_bulan)->translatedFormat('F') }}
                                                </td>

                                                <td class="px-6 py-5 text-gray-600 font-medium">
                                                    {{ $row->periode_tahun }}
                                                </td>

                                                <td class="px-6 py-5 font-bold text-red-600">
                                                    Rp {{ number_format($row->nominal, 0, ',', '.') }}
                                                </td>

                                                <td class="px-6 py-5">
                                                    <span class="inline-flex px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold">
                                                        Belum Lunas
                                                    </span>
                                                </td>

                                                @if(auth()->user()->role !== 'ketua_rt')
                                                <td class="px-6 py-5">
                                                    <div class="flex justify-center gap-2">

                                                        @if(in_array(auth()->user()->role, ['admin','bendahara']))
                                                            <a href="/checkout/{{ $row->id }}/tunai"
                                                            class="bg-gray-100 px-3 py-1.5 rounded text-xs font-bold">
                                                                Tunai
                                                            </a>
                                                        @endif

                                                        @if(in_array(auth()->user()->role, ['admin','bendahara','warga']))
                                                            <a href="/checkout/{{ $row->id }}/online"
                                                            class="bg-blue-600 text-white px-3 py-1.5 rounded text-xs font-bold">
                                                                Online
                                                            </a>
                                                        @endif

                                                    </div>
                                                </td>
                                                @endif

                                            </tr>

                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-6 text-gray-400">
                                                    Tidak ada detail
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </td>
                        </tr>

                        @empty
                        <tbody>
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-400">
                                Tidak ada tunggakan 
                            </td>
                        </tr>
                        </tbody>
                        @endforelse

                </table>
            </div>

        </div>

    </div>
</x-app-layout>