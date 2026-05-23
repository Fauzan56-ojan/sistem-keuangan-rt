<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto space-y-8">

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-xl shadow-sm">
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-red-500 mr-3">error</span>
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Title --}}
       <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('iuran.warga') }}" 
                class="inline-flex items-center justify-center w-10 h-10 bg-white hover:bg-gray-50 text-gray-600 hover:text-gray-900 rounded-xl border border-gray-200 shadow-sm transition-all group shrink-0" 
                title="Kembali">
                    <span class="material-symbols-outlined text-xl group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
                </a>
                
                <h3 class="text-xl font-bold text-gray-800 tracking-tight leading-none">
                    Daftar Tagihan Warga Bulan Ini
                </h3>
            </div>

            {{-- Tempat kosong di kanan (Bisa diisi tombol aksi/cetak jika nanti butuh, otomatis rapi) --}}
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