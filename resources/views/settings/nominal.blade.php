<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold tracking-tight text-slate-800 flex items-center gap-2">
            <span class="material-symbols-outlined text-indigo-600">settings_suggest</span>
            Kelola Nominal Iuran
        </h2>
    </x-slot>

    <div class="p-4 lg:p-8 max-w-7xl mx-auto space-y-8">
        <header class="flex flex-col space-y-1 border-b border-slate-100 pb-6">
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Konfigurasi Iuran</h2>
            <p class="text-slate-500 text-sm font-medium">Atur nominal iuran bulanan dan pantau riwayat perubahan sistem.</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <div class="lg:col-span-5 space-y-4">
                <div class="bg-white p-6 rounded-2xl relative overflow-hidden group shadow-sm border border-slate-200">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-50 rounded-bl-full -mr-10 -mt-10 transition-all group-hover:bg-emerald-100/50"></div>
                    
                    <div class="relative z-10 space-y-5">
                        <div class="flex items-center justify-between">
                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                Status Aktif
                            </span>
                            
                        </div>
                        
                        <div class="space-y-1">
                            <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Nominal Saat Ini</p>
                            <div class="flex items-baseline gap-1">
                                <h3 class="text-3xl font-black text-slate-900">
                                    @if($data->first())
                                        Rp {{ number_format($data->first()->nominal, 0, ',', '.') }}
                                    @else
                                        Rp 0
                                    @endif
                                </h3>
                                <span class="text-sm font-medium text-slate-400">/ bulan</span>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-xl px-4 py-3 border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="material-symbols-outlined text-slate-400 text-sm">event</span>
                                <span class="text-xs font-bold text-slate-600">Periode Tahun {{ date('Y') }}</span>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-indigo-50/50 rounded-xl border border-indigo-100">
                    <div class="flex gap-3">
                        <span class="material-symbols-outlined text-indigo-500 text-xl">info</span>
                        <p class="text-xs text-indigo-900/70 leading-relaxed font-medium">
                            Perubahan akan berdampak pada <strong>seluruh tagihan warga</strong> di periode mendatang. Pastikan data sudah valid sebelum menyimpan.
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7">
                <div class="bg-white p-6 lg:p-8 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-200">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Perbarui Nominal</h3>
                    </div>

                    <form action="{{ route('nominal.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1">Input Nominal Baru</label>
                            <div class="relative group">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400 group-focus-within:text-indigo-600 transition-colors">Rp</div>
                                <input 
                                    name="nominal" 
                                    type="number" 
                                    class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all font-bold text-slate-900 placeholder:font-normal placeholder:text-slate-400" 
                                    placeholder="Contoh: 35000" 
                                    required
                                />
                            </div>
                            <p class="text-[10px] text-slate-400 italic font-medium pl-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[12px]">*</span>
                                Masukkan angka saja tanpa pemisah ribuan
                            </p>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full md:w-auto px-8 py-3 bg-slate-900 text-white rounded-xl font-bold text-sm flex items-center justify-center gap-2 hover:bg-indigo-600 shadow-lg shadow-slate-200 hover:shadow-indigo-200 active:scale-[0.98] transition-all">
                                Simpan Perubahan
                                <span class="material-symbols-outlined text-sm">task_alt</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <section class="space-y-4">
            <div class="flex items-center justify-between px-1">
                <div class="flex items-center gap-2">
                    <span class="w-1 h-5 bg-indigo-500 rounded-full"></span>
                    <h3 class="text-lg font-bold text-slate-800">Riwayat Perubahan</h3>
                </div>
            </div>

            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bulan / Tahun</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Nominal Lama</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Nominal Baru</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal Update</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Oleh Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @php $items = $data; @endphp
                            @foreach($items as $index => $item)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 text-sm">
                                            {{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }}
                                        </span>
                                        <span class="text-[10px] font-medium text-slate-400 uppercase">{{ $item->tahun }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(isset($items[$index + 1]))
                                        <span class="text-xs font-semibold text-slate-400">Rp {{ number_format($items[$index + 1]->nominal, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-slate-300 italic text-[10px]">Data Awal</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="font-bold text-slate-900 text-sm">
                                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                        </span>
                                        @if(isset($items[$index + 1]) && $item->nominal > $items[$index + 1]->nominal)
                                            <span class="material-symbols-outlined text-xs text-emerald-500 bg-emerald-50 p-0.5 rounded">trending_up</span>
                                        @elseif(isset($items[$index + 1]) && $item->nominal < $items[$index + 1]->nominal)
                                            <span class="material-symbols-outlined text-xs text-red-400 bg-red-50 p-0.5 rounded">trending_down</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-medium text-slate-500">
                                        {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
                                        <span class="text-[10px] block text-slate-400 font-normal">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-indigo-50 flex items-center justify-center text-[10px] font-bold text-indigo-600 border border-indigo-100 uppercase">
                                            {{ substr($item->user_name ?? 'A', 0, 1) }}
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">
                                            {{ $item->user_name ?? 'System' }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($items->isEmpty())
                <div class="p-16 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                        <span class="material-symbols-outlined text-slate-300 text-3xl">history_toggle_off</span>
                    </div>
                    <p class="text-slate-400 text-sm font-medium">Belum ada riwayat perubahan data.</p>
                </div>
                @endif
            </div>
        </section>
    </div>
</x-app-layout>