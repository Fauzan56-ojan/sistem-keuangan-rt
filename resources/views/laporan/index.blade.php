<x-app-layout>
    <div class="p-6 md:p-8 max-w-7xl mx-auto space-y-8">
        
        {{-- Header Section --}}
        <section class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Laporan Keuangan</h1>
                <p class="text-gray-500 font-medium">Ringkasan dan detail transaksi keuangan RT</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('laporan.pdf', request()->all()) }}" target="_blank" 
                   class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:bg-slate-800 transition-all shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-xl">picture_as_pdf</span>
                    <span class="text-sm">Export PDF</span>
                </a>
            </div>
        </section>

        {{-- Filter Section --}}
        <section class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div class="space-y-1.5">
                    <label class="text-[10px] uppercase font-bold text-gray-400 tracking-wider ml-1">Jenis Transaksi</label>
                    <select name="jenis" class="block w-full bg-gray-50 border-none rounded-lg px-4 py-2 text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all">
                        <option value="all" {{ $jenis == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="iuran" {{ $jenis == 'iuran' ? 'selected' : '' }}>Iuran</option>
                        <option value="pemasukan" {{ $jenis == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ $jenis == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] uppercase font-bold text-gray-400 tracking-wider ml-1">Bulan</label>
                    <select name="bulan" class="block w-full bg-gray-50 border-none rounded-lg px-4 py-2 text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all">
                        <option value="all" {{ $bulan == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] uppercase font-bold text-gray-400 tracking-wider ml-1">Tahun</label>
                    <select name="tahun" class="block w-full bg-gray-50 border-none rounded-lg px-4 py-2 text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all">
                        <option value="all" {{ $tahun == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                        @foreach ($tahunList as $th)
                            <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>{{ $th }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2 ml-auto">
                    <a href="{{ route('laporan.index', ['jenis' => 'all', 'bulan' => date('n'), 'tahun' => date('Y')]) }}"
                       class="px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                        Reset
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition-all shadow-sm">
                        Filter
                    </button>
                </div>
            </form>
        </section>

        {{-- Summary Cards --}}
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-5">
                <div class="w-14 h-14 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
                    <span class="material-symbols-outlined text-3xl">trending_up</span>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Pemasukan</p>
                    <h3 class="text-xl font-bold text-gray-900">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-5">
                <div class="w-14 h-14 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
                    <span class="material-symbols-outlined text-3xl">trending_down</span>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Pengeluaran</p>
                    <h3 class="text-xl font-bold text-gray-900">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                </div>
            </div>

            <div class="bg-blue-600 p-6 rounded-2xl shadow-lg shadow-blue-100 flex items-center gap-5 relative overflow-hidden">
                <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center text-white z-10">
                    <span class="material-symbols-outlined text-3xl">account_balance_wallet</span>
                </div>
                <div class="z-10">
                    <p class="text-[10px] text-blue-100 font-bold uppercase tracking-wider">Saldo Akhir</p>
                    <h3 class="text-xl font-bold text-white">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                </div>
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full"></div>
            </div>
        </section>

        {{-- Table Section --}}
        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                            @if ($jenis != 'pengeluaran')
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Masuk</th>
                            @endif
                            @if ($jenis != 'pemasukan' && $jenis != 'iuran')
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Keluar</th>
                            @endif
                            @if ($jenis == 'all')
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Saldo</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($transaksi as $t)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-700">
                                {{ \Carbon\Carbon::parse($t['tanggal'])->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $badge = match($t['jenis']) {
                                        'iuran' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                        'pemasukan' => 'bg-green-50 text-green-700 ring-green-600/20',
                                        'pengeluaran' => 'bg-red-50 text-red-700 ring-red-600/20',
                                        default => 'bg-gray-50 text-gray-700 ring-gray-600/20'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ring-1 ring-inset {{ $badge }}">
                                    {{ $t['jenis'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 italic">
                                {{ $t['keterangan'] }}
                            </td>
                            
                            @if ($jenis != 'pengeluaran')
                                <td class="px-6 py-4 text-sm font-bold text-green-600 text-right">
                                    {{ $t['masuk'] ? 'Rp ' . number_format($t['masuk'], 0, ',', '.') : '-' }}
                                </td>
                            @endif

                            @if ($jenis != 'pemasukan' && $jenis != 'iuran')
                                <td class="px-6 py-4 text-sm font-bold text-red-600 text-right">
                                    {{ $t['keluar'] ? 'Rp ' . number_format($t['keluar'], 0, ',', '.') : '-' }}
                                </td>
                            @endif

                            @if ($jenis == 'all')
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 text-right">
                                    Rp {{ number_format($t['saldo'], 0, ',', '.') }}
                                </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-5xl text-gray-200">folder_open</span>
                                    <p class="text-gray-400 font-medium text-sm">Tidak ada transaksi ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($jenis != 'all')
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center gap-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Terfilter:</span>
                <span class="text-lg font-bold text-gray-900">
                    Rp {{ number_format($jenis == 'pengeluaran' ? $totalPengeluaran : $totalPemasukan, 0, ',', '.') }}
                </span>
            </div>
            @endif
        </section>
    </div>
</x-app-layout>