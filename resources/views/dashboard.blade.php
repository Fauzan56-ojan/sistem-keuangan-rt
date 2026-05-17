<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Keuangan RT') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- SUMMARY CARDS -->
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Saldo Kas -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-2xl">
                            <span class="material-symbols-outlined">savings</span>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Total Saldo Kas</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-1">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                </div>

                <!-- Pemasukan -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
                            <span class="material-symbols-outlined">trending_up</span>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Pemasukan (Bulan Ini)</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-1">Rp {{ number_format($totalPemasukanBulan, 0, ',', '.') }}</h3>
                </div>

                <!-- Pengeluaran -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-rose-50 text-rose-600 rounded-2xl">
                            <span class="material-symbols-outlined">trending_down</span>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Pengeluaran (Bulan Ini)</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-1">Rp {{ number_format($totalPengeluaranBulan, 0, ',', '.') }}</h3>
                </div>

                <!-- Tunggakan -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border-l-4 border-l-rose-500 border-y border-r border-slate-100">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-rose-50 text-rose-600 rounded-2xl">
                            <span class="material-symbols-outlined">history_toggle_off</span>
                        </div>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Total Tunggakan</p>
                    <h3 class="text-2xl font-bold text-rose-600 mt-1">Rp {{ number_format($totalTunggakanNominal, 0, ',', '.') }}</h3>
                </div>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- CHART -->
                <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h4 class="font-bold text-lg text-slate-800">Cash Flow Analysis</h4>
                            <p class="text-sm text-slate-400">Analisis perbandingan bulan ke bulan</p>
                        </div>
                    </div>
                    <div class="h-72">
                        <canvas id="cashflowChart"></canvas>
                    </div>
                </div>

                <!-- PAYMENT PROGRESS -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-lg text-slate-800">Progress Iuran</h4>
                        <p class="text-sm text-slate-400 mb-8">Status pembayaran bulan ini</p>
                        
                        <div class="relative flex justify-center items-center mb-8">
                            <div 
                                class="w-40 h-40 rounded-full flex items-center justify-center relative"
                                style="
                                    background:
                                    conic-gradient(
                                        #10b981 {{ $persen }}%,
                                        #f1f5f9 {{ $persen }}%
                                    );
                                "
                            >
                                <div class="w-28 h-28 bg-white rounded-full flex flex-col items-center justify-center">
                                    <span class="text-3xl font-extrabold text-slate-900">
                                        {{ $persen }}%
                                    </span>

                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                        Terbayar
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-emerald-50">
                                <div class="flex items-center gap-3">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-sm font-semibold text-emerald-900">Sudah Bayar</span>
                                </div>
                                <span class="text-sm font-bold text-emerald-700">{{ $sudahBayar }} Warga</span>
                            </div>
                            @if(auth()->user()->role === 'warga')
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-rose-50">
                                <div class="flex items-center gap-3">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span class="text-sm font-semibold text-rose-900">Belum Bayar</span>
                                </div>
                                <span class="text-sm font-bold text-rose-700">{{ $belumBayar }} Warga</span>
                            </div>
                        @else
                            <a href="{{ route('belum.bayar') }}"
                            class="flex items-center justify-between p-4 rounded-2xl bg-rose-50 hover:bg-rose-100 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    <span class="text-sm font-semibold text-rose-900">Belum Bayar</span>
                                </div>
                                <span class="text-sm font-bold text-rose-700">
                                    {{ $belumBayar }} Warga 
                                    <span class="material-symbols-outlined text-xs align-middle">chevron_right</span>
                                </span>
                            </a>
                        @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- RECENT TRANSACTIONS & UNPAID LIST -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
                <!-- Transaction Table -->
                <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                        <h4 class="font-bold text-lg text-slate-800">Transaksi Terakhir</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50 text-[10px] uppercase font-bold text-slate-400 tracking-widest">
                                <tr>
                                    <th class="px-8 py-4">Kategori</th>
                                    <th class="px-8 py-4">Keterangan</th>
                                    <th class="px-8 py-4 text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                {{-- IURAN TERAKHIR --}}
                                @if($pembayaranTerakhir)
                                <tr onclick="window.location='{{ auth()->user()->role === 'warga'
                                    ? url('/warga/' . auth()->id() . '/iuran') 
                                    : route('iuran.warga') }}'" class="group cursor-pointer hover:bg-emerald-50/60 transition active:scale-[0.995]">
                                    <td class="px-8 py-4"><span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold uppercase">Iuran Bulanan</span></td>
                                    <td class="px-8 py-4">
                                        <p class="text-sm font-bold text-slate-800">{{ $pembayaranTerakhir->user->name }} {{ $pembayaranTerakhir->user->nomor_rumah ?? '-' }}</p>
                                        <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($pembayaranTerakhir->paid_at)->format('d M Y') }}</p>
                                    </td>
                                    <td class="px-8 py-4 text-right text-sm font-bold text-emerald-600">+ Rp {{ number_format($pembayaranTerakhir->amount, 0, ',', '.') }}</td>
                                </tr>
                                @endif

                                
                                {{-- PEMASUKAN LAINNYA --}}
                                @if($pemasukanTerakhir)
                                <tr onclick="window.location='{{ route('pemasukan.index') }}'" class="group cursor-pointer hover:bg-blue-50/60 transition active:scale-[0.995]">
                                    <td class="px-8 py-4"><span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-[10px] font-bold uppercase">Pemasukan</span></td>
                                    <td class="px-8 py-4">
                                        <p class="text-sm font-bold text-slate-800">{{ $pemasukanTerakhir->keterangan }}</p>
                                        <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($pemasukanTerakhir->tanggal)->format('d M Y') }}</p>
                                    </td>                                    
                                    <td class="px-8 py-4 text-right text-sm font-bold text-slate-900">+ Rp {{ number_format($pemasukanTerakhir->nominal, 0, ',', '.') }}</td>
                                </tr>
                                @endif

                                {{-- PENGELUARAN TERAKHIR --}}
                                @if($pengeluaranTerakhir)
                                <tr onclick="window.location='{{ route('pengeluaran.index') }}'" class="group cursor-pointer hover:bg-rose-50/60 transition active:scale-[0.995]">
                                    <td class="px-8 py-4"><span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-full text-[10px] font-bold uppercase">Pengeluaran</span></td>
                                    <td class="px-8 py-4">
                                        <p class="text-sm font-bold text-slate-800">{{ $pengeluaranTerakhir->keterangan }}</p>
                                        <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($pengeluaranTerakhir->tanggal)->format('d M Y') }}</p>
                                    </td>
                                    <td class="px-8 py-4 text-right text-sm font-bold text-rose-600">- Rp {{ number_format($pengeluaranTerakhir->nominal, 0, ',', '.') }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                @if(auth()->user()->role !== 'warga')
                <!-- Warga Menunggak -->
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                        <div class="flex justify-between items-center mb-6">
                            <h4 class="font-bold text-slate-800 text-sm">Warga Menunggak</h4>
                            <span class="px-2 py-1 bg-rose-50 text-rose-600 rounded-lg text-[10px] font-bold">
                                {{ $totalTunggakanOrang }} Orang
                            </span>
                        </div>

                        <div class="space-y-5"> {{-- Menambah jarak antar baris --}}
                            @forelse($dataTunggakan as $item)
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    {{-- Avatar dengan inisial lebih soft --}}
                                    <div class="w-9 h-9 rounded-xl bg-slate-50 flex items-center justify-center text-[11px] font-bold text-slate-500 uppercase border border-slate-100 group-hover:bg-rose-50 group-hover:text-rose-600 transition-colors">
                                        {{ substr($item['nama'], 0, 2) }}
                                    </div>

                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-700 leading-none mb-1">
                                            {{ $item['nama'] }}
                                        </span>
                                        <span class="text-[10px] text-slate-400">
                                            Alamat: {{ $item['nomor_rumah'] ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <span class="block text-xs font-black text-rose-600">
                                        {{ $item['jumlah_bulan'] }} bln
                                    </span>
                                    <span class="text-[9px] uppercase tracking-wider text-slate-300 font-bold">Tunggakan</span>
                                </div>
                            </div>
                            @empty
                                <div class="text-center py-6">
                                    <p class="text-xs text-slate-400 italic">Tidak ada tunggakan</p>
                                </div>
                            @endforelse
                        </div>

                        <a href="{{ route('tunggakan.index') }}" class="block w-full mt-6 py-3 text-center bg-slate-50 hover:bg-slate-100 rounded-2xl text-[10px] font-bold text-slate-500 uppercase tracking-widest transition-all">
                            Lihat Semua Detail
                        </a>
                    </div>
                </div>
                @else

                <div class="space-y-6">

                    {{-- CARD 1: TUNGGAKAN --}}
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                        <h4 class="font-bold text-slate-800 text-sm mb-4">
                            Tunggakan Anda
                        </h4>

                        @if($jumlahTunggakan > 0)
                            <div class="text-center">
                                <p class="text-2xl font-black text-rose-600">
                                    {{ $jumlahTunggakan }} Bulan
                                </p>
                                <p class="text-xs text-slate-400 mt-1">
                                    Anda memiliki tunggakan
                                </p>

                                <a href="{{ url('/tunggakan/' . auth()->id()) }}"
                                class="block mt-4 bg-rose-50 text-rose-600 py-2 rounded-xl text-xs font-bold">
                                    Lihat Detail
                                </a>
                            </div>
                        @else
                            <p class="text-center text-emerald-600 font-bold text-sm">
                                Tidak ada tunggakan 🎉
                            </p>
                        @endif
                    </div>

                    {{-- CARD 2: STATUS BULAN INI --}}
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                        <h4 class="font-bold text-slate-800 text-sm mb-4">
                            Status Bulan Ini
                        </h4>

                        @if($sudahBayarBulanIni ?? false)
                            <p class="text-center text-emerald-600 font-bold text-sm">
                                Sudah bayar ✔️
                            </p>
                        @else
                            <div class="text-center">
                                <p class="text-rose-600 font-bold text-sm">
                                    Belum bayar bulan ini
                                </p>

                                <a href="{{ url('/warga/' . auth()->id() . '/iuran') }}"
                                class="block mt-4 bg-emerald-500 text-white py-2 rounded-xl text-xs font-bold">
                                    Bayar Sekarang
                                </a>
                            </div>
                        @endif
                    </div>

                </div>

                @endif

        </div>

    </div>


            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('cashflowChart');
        const rawData = @json($cashflow);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: rawData.map(item => item.bulan),
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: rawData.map(item => item.pemasukan),
                        backgroundColor: '#10b981', // emerald-500
                        borderRadius: 6,
                        barThickness: 12,
                    },
                    {
                        label: 'Pengeluaran',
                        data: rawData.map(item => item.pengeluaran),
                        backgroundColor: '#f87171', // rose-400
                        borderRadius: 6,
                        barThickness: 12,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f8fafc', drawBorder: false },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10, weight: 'bold' } }
                    }
                }
            }
        });
    </script>
</x-app-layout>