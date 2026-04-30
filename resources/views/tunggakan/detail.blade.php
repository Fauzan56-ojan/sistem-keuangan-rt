<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto space-y-8">
        
        {{-- Header Section --}}
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white rounded-2xl p-8 border border-gray-200 shadow-sm flex items-start justify-between relative overflow-hidden">
                <div class="flex gap-6 z-10">
                    <div class="w-20 h-20 rounded-2xl bg-amber-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-amber-600 text-4xl">pending_actions</span>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $warga->name }}</h3>
                        <div class="flex items-center gap-2 text-gray-500">
                            <span class="material-symbols-outlined text-sm">location_on</span>
                            <p class="text-sm font-medium">No. Rumah: {{ $warga->nomor_rumah ?? '-' }}</p>
                        </div>
                        <div class="pt-2">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Daftar Tunggakan</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4 z-10">
                    <div class="w-12 h-12 rounded-xl bg-white text-red-600 flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Tunggakan</p>
                        <p class="text-lg font-bold text-gray-900">
                            {{ $iuran->count() }} Bulan
                        </p>
                    </div>
                </div>
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-red-50/50 rounded-full"></div>
            </div>
        </section>

        {{-- Title Section --}}
        <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xl font-bold text-gray-800 tracking-tight">Rincian Belum Terbayar</h3>
        </section>

        {{-- Table Section --}}
        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Bulan</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tahun</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nominal</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            @if(auth()->user()->role !== 'ketua_rt')
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($iuran as $row)
                        <tr class="hover:bg-red-50/30 transition-colors">
                            <td class="px-6 py-5 font-semibold text-gray-900">
                                {{ \Carbon\Carbon::create()->month($row->periode_bulan)->translatedFormat('F') }}
                            </td>
                            <td class="px-6 py-5 text-gray-600 font-medium">{{ $row->periode_tahun }}</td>
                            <td class="px-6 py-5 font-bold text-red-600">
                                Rp {{ number_format($row->nominal, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold ring-1 ring-inset ring-red-600/20">
                                    Belum Lunas
                                </span>
                            </td>
                            @if(auth()->user()->role !== 'ketua_rt')
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    @if(in_array(auth()->user()->role, ['admin','bendahara']))
                                        <a href="/checkout/{{ $row->id }}/tunai" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-gray-200 transition-all border border-gray-200">
                                            Tunai
                                        </a>
                                    @endif

                                    @if(in_array(auth()->user()->role, ['admin','bendahara','warga']))
                                        <a href="/checkout/{{ $row->id }}/online" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-700 shadow-sm shadow-blue-200 transition-all">
                                            Bayar Online
                                        </a>
                                    @endif
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                                <div class="flex flex-col items-center">
                                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">check_circle</span>
                                    <p>Tidak ada tunggakan iuran.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                <p class="text-xs font-medium text-gray-500 italic">
                    *Segera lakukan pembayaran
                </p>
            </div>
        </section>
    </div>
</x-app-layout>