<x-app-layout>
    <div class="pt-8 pb-12 px-4 md:px-8 max-w-7xl mx-auto font-['Manrope']">
        
        <div class="mb-10">
            <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 mb-2">Laporan Keuangan</h1>
            <p class="text-slate-500 text-lg font-medium">Ringkasan dan detail transaksi keuangan RT</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 group hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-emerald-50 rounded-2xl">
                        <span class="material-symbols-outlined text-emerald-600 text-3xl">trending_up</span>
                    </div>
                </div>
                <p class="text-slate-500 font-semibold text-xs uppercase tracking-wider mb-1">Total Pemasukan</p>
                <h3 class="text-3xl font-extrabold text-slate-900">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 group hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-red-50 rounded-2xl">
                        <span class="material-symbols-outlined text-red-600 text-3xl">trending_down</span>
                    </div>
                </div>
                <p class="text-slate-500 font-semibold text-xs uppercase tracking-wider mb-1">Total Pengeluaran</p>
                <h3 class="text-3xl font-extrabold text-slate-900">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
            </div>

            <div class="bg-indigo-600 p-8 rounded-2xl shadow-xl relative overflow-hidden group">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <div class="p-3 bg-white/20 rounded-2xl">
                        <span class="material-symbols-outlined text-white text-3xl">account_balance</span>
                    </div>
                    <span class="text-[10px] font-bold text-white bg-white/20 px-3 py-1 rounded-full uppercase tracking-widest">Live Balance</span>
                </div>
                <p class="text-indigo-100 font-semibold text-xs uppercase tracking-wider mb-1 relative z-10">Saldo Akhir</p>
                <h3 class="text-4xl font-extrabold text-white relative z-10">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white border border-slate-100 rounded-2xl p-6 mb-8 flex flex-wrap items-end justify-between gap-6 shadow-sm">
            <form method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex flex-col">
                    <label class="text-[10px] uppercase font-extrabold text-slate-400 tracking-widest mb-2 ml-1">Jenis Transaksi</label>
                    <select name="jenis" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl py-2.5 pl-4 pr-10 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 cursor-pointer transition-all">
                        <option value="all" {{ $jenis == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="iuran" {{ $jenis == 'iuran' ? 'selected' : '' }}>Iuran</option>
                        <option value="pemasukan" {{ $jenis == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ $jenis == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-[10px] uppercase font-extrabold text-slate-400 tracking-widest mb-2 ml-1">Bulan</label>
                    <select name="bulan" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl py-2.5 pl-4 pr-10 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 cursor-pointer transition-all">
                        <option value="all" {{ $bulan == 'all' ? 'selected' : '' }}>
                            Semua Bulan
                        </option>

                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-[10px] uppercase font-extrabold text-slate-400 tracking-widest mb-2 ml-1">Tahun</label>
                    <select name="tahun" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl py-2.5 pl-4 pr-10 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 cursor-pointer transition-all">
                        <option value="all" {{ $tahun == 'all' ? 'selected' : '' }}>
                            Semua Tahun
                        </option>

                        @foreach ($tahunList as $th)
                            <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>
                                {{ $th }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            <a href="{{ route('laporan.index', [
                'jenis' => 'all',
                'bulan' => date('n'),
                'tahun' => date('Y')
            ]) }}"
            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-xl font-bold hover:bg-gray-300 transition">
                Reset
            </a>

            <a href="{{ route('laporan.pdf', request()->all()) }}" target="_blank" class="bg-slate-900 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 hover:bg-slate-800 transition-all active:scale-95 shadow-lg shadow-slate-200">
                <span class="material-symbols-outlined text-xl">picture_as_pdf</span>
                Export PDF
            </a>
        </div>

        <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.15em]">Tanggal</th>
                            <th class="px-8 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.15em]">Jenis</th>
                            <th class="px-8 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.15em]">Keterangan</th>
                            @if ($jenis != 'pengeluaran')
                                <th class="px-8 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.15em] text-right">Masuk</th>
                            @endif
                            @if ($jenis != 'pemasukan' && $jenis != 'iuran')
                                <th class="px-8 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.15em] text-right">Keluar</th>
                            @endif
                            @if ($jenis == 'all')
                                <th class="px-8 py-5 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.15em] text-right">Saldo</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($transaksi as $t)
                        <tr class="hover:bg-slate-50 transition-colors cursor-default">
                            <td class="px-8 py-5 text-sm font-semibold text-slate-700">
                                {{ \Carbon\Carbon::parse($t['tanggal'])->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-8 py-5">
                                @php
                                    $badgeClass = match($t['jenis']) {
                                        'iuran' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'pemasukan' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'pengeluaran' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        default => 'bg-slate-50 text-slate-600 border-slate-100'
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter border {{ $badgeClass }}">
                                    {{ $t['jenis'] }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-500 italic">
                                {{ $t['keterangan'] }}
                            </td>
                            
                            @if ($jenis != 'pengeluaran')
                                <td class="px-8 py-5 text-sm font-bold text-emerald-600 text-right">
                                    {{ $t['masuk'] ? 'Rp ' . number_format($t['masuk'], 0, ',', '.') : '-' }}
                                </td>
                            @endif

                            @if ($jenis != 'pemasukan' && $jenis != 'iuran')
                                <td class="px-8 py-5 text-sm font-bold text-rose-600 text-right">
                                    {{ $t['keluar'] ? 'Rp ' . number_format($t['keluar'], 0, ',', '.') : '-' }}
                                </td>
                            @endif

                            @if ($jenis == 'all')
                                <td class="px-8 py-5 text-sm font-extrabold text-slate-900 text-right">
                                    Rp {{ number_format($t['saldo'], 0, ',', '.') }}
                                </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="material-symbols-outlined text-5xl text-slate-200 mb-4">receipt_long</span>
                                    <p class="text-slate-400 font-medium">Tidak ada transaksi ditemukan pada periode ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($jenis != 'all')
            <div class="px-8 py-4 bg-slate-50/80 border-t border-slate-100 flex justify-end items-center gap-4">
                <span class="text-xs font-bold text-slate-400 uppercase">Total:</span>
                <span class="text-lg font-black text-slate-900">
                    Rp {{ number_format($jenis == 'pengeluaran' ? $totalPengeluaran : $totalPemasukan, 0, ',', '.') }}
                </span>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>