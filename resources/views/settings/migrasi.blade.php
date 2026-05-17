<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-emerald-900 dark:text-emerald-50 leading-tight">
            {{ __('Migrasi Data Iuran') }}
        </h2>
    </x-slot>

    <div class="p-6 lg:p-10 max-w-6xl mx-auto w-full">
        <div class="mb-10">
            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-1">Buat Tagihan Iuran Sebelumnya</h2>
            <p class="text-sm text-slate-500 dark:text-emerald-400/80">Buat tagihan iuran untuk bulan-bulan sebelumnya yang belum tercatat di sistem</p>
        </div>

        {{-- Notifikasi --}}
        @if(session('success') || session('error'))
        <div class="mb-8 max-w-2xl">

            @if(session('error'))
                <div class="flex items-center gap-3 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-xl">
                    <span class="material-symbols-outlined">error</span>
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
            
            {{-- Form Section --}}
            <div class="lg:col-span-7">
                <div class="bg-white dark:bg-emerald-950/40 border border-slate-200 dark:border-emerald-800/50 rounded-2xl p-8 shadow-sm">
                    <form action="{{ route('settings.migrasi.proses') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-emerald-100 flex items-center gap-2" for="year">
                                <span class="material-symbols-outlined text-[18px]">calendar_today</span> Tahun
                            </label>
                            <input 
                                name="tahun"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-emerald-900/10 border border-slate-200 dark:border-emerald-800 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all outline-none text-slate-900 dark:text-white font-medium" 
                                id="year" 
                                placeholder="Contoh: 2024" 
                                type="number"
                                required
                            />
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 dark:text-emerald-100 flex items-center gap-2" for="month">
                                <span class="material-symbols-outlined text-[18px]">event_note</span> Sampai Bulan
                            </label>
                            <div class="relative">
                                <select 
                                    name="bulan"
                                    class="w-full pl-4 pr-10 py-3 bg-slate-50 dark:bg-emerald-900/10 border border-slate-200 dark:border-emerald-800 rounded-xl focus:ring-2 focus:ring-emerald-500 appearance-none transition-all outline-none text-slate-900 dark:text-white font-medium" 
                                    id="month"
                                    required
                                >
                                    @php
                                        $bulanNama = [
                                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                        ];
                                    @endphp
                                    @foreach($bulanNama as $val => $nama)
                                        <option value="{{ $val }}" {{ $val == 12 ? 'selected' : '' }}>{{ $nama }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                                    
                                </div>
                                <p class="text-[11px] text-slate-500 mt-2 italic">*Sistem akan membuat tagihan dari bulan <strong>Januari</strong> hingga bulan yang dipilih</p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-emerald-800/30">
                            <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-6 rounded-xl shadow-md hover:shadow-lg active:scale-[0.98] transition-all flex items-center justify-center gap-3" type="submit">
                                Buat Tagihan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Info Section --}}
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-amber-50 dark:bg-amber-900/10 border-l-4 border-amber-400 p-6 rounded-r-2xl">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-amber-600" style="font-variation-settings: 'FILL' 1;">warning</span>
                        <h3 class="font-bold text-amber-950 dark:text-amber-200">Perhatian Penting</h3>
                    </div>
                    <ul class="text-amber-900/80 dark:text-amber-300/80 text-sm space-y-4 font-medium leading-relaxed">
                        <li class="flex gap-3">
                            <span class="bg-amber-200 dark:bg-amber-800 h-5 w-5 rounded-full flex items-center justify-center text-[10px] shrink-0">1</span>
                            <span>Tagihan akan dibuat mulai dari <strong>Januari</strong> hingga bulan yang Anda pilih.</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="bg-amber-200 dark:bg-amber-800 h-5 w-5 rounded-full flex items-center justify-center text-[10px] shrink-0">2</span>
                            <span>Tagihan yang sudah ada tidak akan dibuat ulang (mencegah duplikasi).</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="bg-amber-200 dark:bg-amber-800 h-5 w-5 rounded-full flex items-center justify-center text-[10px] shrink-0">3</span>
                            <span>Pastikan periode yang dipilih sudah benar, karena proses ini tidak bisa dibatalkan.</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>