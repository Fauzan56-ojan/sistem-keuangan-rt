<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto space-y-8">
        
        {{-- Header & Alert Section --}}
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-xl shadow-sm">
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-red-500 mr-3">error</span>
                    <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white rounded-2xl p-8 border border-gray-200 shadow-sm flex items-start justify-between relative overflow-hidden">
                <div class="flex gap-6 z-10">
                    <div class="w-20 h-20 rounded-2xl bg-blue-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 text-4xl">person</span>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $warga->name }}</h3>
                        <div class="flex items-center gap-2 text-gray-500">
                            <span class="material-symbols-outlined text-sm">location_on</span>
                            <p class="text-sm font-medium">Alamat: {{ $warga->nomor_rumah ?? '-' }}</p>
                        </div>
                        <div class="pt-2">
                            @if($warga->status_aktif)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Aktif</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Nonaktif</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4 z-10">
                    <div class="w-12 h-12 rounded-xl bg-white text-blue-600 flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined">event_available</span>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Status Terakhir</p>
                        <p class="text-lg font-bold text-gray-900">
                            @if($lastPaid)
                                {{ \Carbon\Carbon::create()->month($lastPaid->periode_bulan)->translatedFormat('F') }} - Lunas
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-50/50 rounded-full"></div>
            </div>
        </section>

        <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xl font-bold text-gray-800 tracking-tight">Iuran Tahun {{ $tahun }}</h3>
            
            <form method="GET" class="flex items-center gap-3 bg-white p-2 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-xs font-bold text-gray-500 uppercase ml-2">Pilih Tahun:</p>
                <div class="relative">
                    <select name="tahun" onchange="this.form.submit()" class="appearance-none bg-gray-50 border-none rounded-lg px-4 py-1.5 pr-10 text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 outline-none cursor-pointer transition-all">
                        @foreach ($tahunList as $th)
                            <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>{{ $th }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400 text-sm">expand_more</span>
                </div>
            </form>
        </section>

        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Bulan</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tahun</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nominal</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Bayar</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Metode</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($iuran as $row)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-5 font-semibold text-gray-900">
                                {{ \Carbon\Carbon::create()->month($row->periode_bulan)->translatedFormat('F') }}
                            </td>
                            <td class="px-6 py-5 text-gray-600 font-medium">{{ $row->periode_tahun }}</td>
                            <td class="px-6 py-5 font-bold text-gray-900">
                                Rp {{ number_format($row->nominal, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-5">
                                @if($row->status == 'paid')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold ring-1 ring-inset ring-green-600/20">
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold ring-1 ring-inset ring-red-600/20">
                                        Belum Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-sm text-gray-500">
                                {{ $row->pembayaran?->paid_at ? \Carbon\Carbon::parse($row->pembayaran->paid_at)->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-5 text-sm text-gray-600">
                                {{ $row->pembayaran?->metode ? ucfirst($row->pembayaran->metode) : '-' }}
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    @if($row->status == 'pending')
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
                                    @else
                                        <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Lihat Detail">
                                            <span class="material-symbols-outlined">receipt_long</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Footer Pagination Simple --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500 italic">
                    Menampilkan daftar iuran periode {{ $tahun }}
                </p>
            </div>
        </section>
    </div>
</x-app-layout>