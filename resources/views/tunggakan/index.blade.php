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
                            <th class="px-6 py-4">Jumlah Bulan</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        @forelse ($data as $item)

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
                                <a href="/tunggakan/{{ $item['user_id'] }}"
                                   class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-full text-xs font-semibold hover:bg-emerald-100 transition">
                                    Detail
                                </a>
                            </td>

                        </tr>

                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-400">
                                Tidak ada tunggakan 🎉
                            </td>
                        </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

    </div>
</x-app-layout>