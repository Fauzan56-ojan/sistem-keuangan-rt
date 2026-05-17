<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-emerald-900 dark:text-emerald-50 leading-tight">
            {{ __('Generate Iuran') }}
        </h2>
    </x-slot>

    {{-- Main Container: Dibatasi max-w-2xl agar tidak terlalu lebar (proporsional) --}}
    <div class="p-6 lg:p-10 max-w-2xl">
        
        <header class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight mb-2">
                Tagihan Iuran Tahunan
            </h1>
            <p class="text-slate-500 dark:text-emerald-400/80 text-sm leading-relaxed">
                Sistem akan membuat tagihan iuran baru dari <strong>Januari hingga Desember</strong> untuk semua warga secara otomatis.
            </p>
        </header>

        {{-- Notifikasi Error/Success --}}

        @if(session('error'))
            <div class="flex items-center gap-3 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-xl">
                <span class="material-symbols-outlined">error</span>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <div class="flex flex-col gap-8">
            <section class="bg-white dark:bg-emerald-950/40 border border-slate-200 dark:border-emerald-800/50 rounded-2xl p-6 shadow-sm">
                <form action="{{ route('iuran.generate') }}" method="POST" class="flex flex-col gap-5">
                    @csrf
                    
                    <div class="grid grid-cols-1 gap-2">
                        <label class="text-sm font-bold text-slate-700 dark:text-emerald-100 flex items-center gap-2" for="tahun">
                            <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                            Pilih Tahun Tagihan
                        </label>
                        
                        <div class="relative group">
                            <select 
                                name="tahun" 
                                id="tahun"
                                class="w-full bg-slate-50 dark:bg-emerald-900/10 border border-slate-200 dark:border-emerald-800 rounded-xl py-3 px-4 text-slate-900 dark:text-white font-medium focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all appearance-none cursor-pointer outline-none"
                            >
                                @php
                                    $currentYear = date('Y');
                                    $nextYear = $currentYear + 1;
                                @endphp
                                <option value="{{ $currentYear }}">{{ $currentYear }}</option>
                                <option value="{{ $nextYear }}" selected>{{ $nextYear }}</option>
                                <option value="{{ $nextYear + 1 }}">{{ $nextYear + 1 }}</option>
                            </select>                        
                    
                        </div>
                    </div>

                    <div class="pt-2">
                        <button 
                            type="submit"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl py-3.5 font-bold text-base shadow-md hover:shadow-lg hover:shadow-emerald-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-3"
                        >
                            
                            Buat Tagihan Baru
                        </button>
                    </div>
                </form>
            </section>

            <div class="flex gap-3 px-2">
                <span class="material-symbols-outlined text-amber-500 text-xl">info</span>
                <p class="text-xs text-slate-400 dark:text-emerald-500/60 italic leading-normal">
                    Proses ini akan memeriksa data warga yang aktif dan membuat 12 record tagihan untuk setiap warga pada tahun yang dipilih.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>