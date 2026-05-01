<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto space-y-8">

        {{-- Title --}}
        <section class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-800">
                Warga Belum Bayar ({{ now()->translatedFormat('F Y') }})
            </h3>
        </section>

        {{-- Table --}}
        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">No. Rumah</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Nominal</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                            @if(auth()->user()->role !== 'ketua_rt')
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($data as $row)
                        <tr class="hover:bg-red-50/30 transition-colors">

                            {{-- Nama --}}
                            <td class="px-6 py-5 font-semibold text-gray-900">
                                {{ $row->user->name }}
                            </td>

                            {{-- Nomor Rumah --}}
                            <td class="px-6 py-5 text-gray-600">
                                {{ $row->user->nomor_rumah ?? '-' }}
                            </td>

                            {{-- Nominal --}}
                            <td class="px-6 py-5 font-bold text-red-600">
                                Rp {{ number_format($row->nominal, 0, ',', '.') }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-5">
                                <span class="inline-flex px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold">
                                    Belum Bayar
                                </span>
                            </td>

                            {{-- Aksi --}}
                            @if(auth()->user()->role !== 'ketua_rt')
                            <td class="px-6 py-5">
                                <div class="flex justify-center gap-2">

                                    @if(in_array(auth()->user()->role, ['admin','bendahara']))
                                        <a href="/checkout/{{ $row->id }}/tunai"
                                        class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-gray-200 transition-all border border-gray-200">
                                            Tunai
                                        </a>
                                    @endif

                                    @if(in_array(auth()->user()->role, ['admin','bendahara','warga']))
                                        <a href="/checkout/{{ $row->id }}/online"
                                        class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-700 shadow-sm shadow-blue-200 transition-all">
                                            Bayar Online
                                        </a>
                                    @endif

                                </div>
                            </td>
                            @endif

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">check_circle</span>
                                    <p>Semua warga sudah bayar 🎉</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            
        </section>

    </div>
</x-app-layout>