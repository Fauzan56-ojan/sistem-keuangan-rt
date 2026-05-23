<x-app-layout>
   <div class="p-6 max-w-7xl mx-auto space-y-6">
        
    {{-- Header & Alert Section --}}
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded-r-xl shadow-sm">
            <div class="flex items-center">
                <span class="material-symbols-outlined text-red-500 mr-3">error</span>
                <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- KONSISTEN: Judul Utama Halaman Berada Paling Atas --}}
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('iuran.warga') }}" 
                class="inline-flex items-center justify-center w-10 h-10 bg-white hover:bg-gray-50 text-gray-600 hover:text-gray-900 rounded-xl border border-gray-200 shadow-sm transition-all group" 
                title="Kembali">
                    <span class="material-symbols-outlined text-xl group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
                </a>
            
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight leading-none">
                Iuran Tahun {{ $tahun }}
            </h1>
        </div>

        {{-- Dropdown Pilih Tahun --}}
        <form method="GET" class="flex items-center gap-3 bg-white p-1.5 rounded-xl border border-gray-200 shadow-sm w-fit h-10">
            <p class="text-xs font-bold text-gray-500 uppercase ml-2 tracking-wider whitespace-nowrap">Pilih Tahun:</p>
            <div class="relative h-full flex items-center">
                <select name="tahun" onchange="this.form.submit()" class="appearance-none bg-gray-50 border-none rounded-lg pl-3 pr-8 py-1 text-sm font-semibold focus:ring-2 focus:ring-blue-500/20 outline-none cursor-pointer transition-all h-full">
                    @foreach ($tahunList as $th)
                        <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>{{ $th }}</option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400 text-sm">expand_more</span>
            </div>
        </form>
    </section>

    {{-- Profil Warga Card (Sekarang di bawah Judul Utama) --}}
    <section class="space-y-4">
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
            <div class="flex items-center gap-6 z-10">
                <div class="w-16 h-16 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-blue-600 text-3xl">person</span>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <h3 class="text-xl font-bold text-gray-900">{{ $warga->name }}</h3>
                        @if($warga->status_aktif)
                            <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-green-100 text-green-700">Aktif</span>
                        @else
                            <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-red-100 text-red-700">Nonaktif</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 text-gray-500">
                        <span class="material-symbols-outlined text-sm">location_on</span>
                        <p class="text-sm font-medium">Alamat: {{ $warga->nomor_rumah ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Status Terakhir di Sebelah Kanan Card --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 z-10 w-full md:w-auto justify-end">
                <div class="bg-gray-50 px-4 py-2 rounded-xl border border-gray-100 flex items-center gap-3 h-12 w-full sm:w-auto">
                    <span class="material-symbols-outlined text-blue-600 text-xl">event_available</span>
                    <div>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider leading-none">Status Terakhir</p>
                        <p class="text-sm font-bold text-gray-900 mt-0.5">
                            @if($lastPaid)
                                {{ \Carbon\Carbon::create()->month($lastPaid->periode_bulan)->translatedFormat('F') }} - Lunas
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-blue-50/30 rounded-full"></div>
        </div>
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
                                {{ $row->pembayaran?->metode ? strtoupper($row->pembayaran->metode) : '-' }}
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
                                        <button onclick="document.getElementById('modal{{ $row->id }}').classList.remove('hidden')"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">

                                            <span class="material-symbols-outlined">receipt_long</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <!-- Modal Background -->
                        <div id="modal{{ $row->id }}" 
                            class="hidden fixed inset-0 bg-slate-900/60 flex items-center justify-center z-50 p-4">

                            <!-- Modal Card -->
                            <div id="bukti{{ $row->id }}" class="bg-white rounded-none shadow-2xl w-full max-w-sm overflow-hidden border border-slate-200">
                                
                                <!-- Header / Aksesori Visual -->
                                <div class="bg-blue-600 p-4 text-white text-center">
                                    <div class="w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-2 shadow-md ring-2 ring-green-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <h2 class="font-bold text-lg uppercase tracking-wider">Bukti Pembayaran</h2>
                                    <p class="text-xs text-blue-100 opacity-80">Terima kasih telah melakukan pembayaran</p>
                                </div>

                                <!-- Body Konteks Kwitansi -->
                                <div class="p-6 space-y-4">
                                    <!-- Info Utama (Nominal) -->
                                    <div class="text-center py-2 border-b border-dashed border-slate-200 mb-4">
                                        <span class="text-slate-500 text-xs uppercase block mb-1">Total Nominal</span>
                                        <span class="text-2xl font-bold text-slate-800">Rp {{ number_format($row->nominal,0,',','.') }}</span>
                                    </div>

                                    <!-- Detail List -->
                                    <div class="space-y-3 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-slate-500">Kode Transaksi</span>
                                            <span class="font-mono text-xs text-slate-700 text-right break-all max-w-[180px]">
                                                {{ $row->pembayaran?->kode_transaksi ?? $row->pembayaran?->order_id ?? '-' }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-500">Nama Lengkap</span>
                                            <span class="font-semibold text-slate-700 text-right">{{ $warga->name }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-500">Alamat</span>
                                            <span class="font-semibold text-slate-700 text-right">{{ $warga->nomor_rumah ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-500">Tanggal Bayar</span>
                                            <span class="font-semibold text-slate-700">
                                                {{ $row->pembayaran?->paid_at 
                                                    ? \Carbon\Carbon::parse($row->pembayaran->paid_at)->translatedFormat('d M Y • H:i')
                                                    : '-' }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-500">Periode</span>
                                            <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::create()->month($row->periode_bulan)->translatedFormat('F') }}</span>
                                        </div>                                        
                                        <div class="flex justify-between">
                                            <span class="text-slate-500">Metode</span>
                                            <span class="font-semibold text-slate-700">{{ strtoupper($row->pembayaran?->metode ?? '-') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <!-- Bagian Action Buttons di dalam Modal -->
                                <div class="p-4 bg-slate-50 flex gap-2" data-html2canvas-ignore>
                                    <button onclick="downloadStruk({{ $row->id }})" 
                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2 text-sm">
                                        <span class="material-symbols-outlined text-sm">print</span>
                                        Cetak Bukti
                                    </button>

                                    <button onclick="document.getElementById('modal{{ $row->id }}').classList.add('hidden')"
                                            class="flex-1 border border-slate-300 hover:bg-slate-100 text-slate-600 font-medium py-2 px-4 rounded-lg transition-colors text-sm">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
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
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
function downloadStruk(id) {
    const element = document.getElementById('bukti' + id);
    
    const options = {
        scale: 3,
        useCORS: true,
        backgroundColor: "#ffffff",
        logging: false,
        scrollX: 0,
        scrollY: -window.scrollY,
        onclone: (clonedDoc) => {
            const card = clonedDoc.getElementById('bukti' + id);
            const header = clonedDoc.getElementById('header-bukti-' + id);
            
            if (card) {
                card.style.borderRadius = "0px";
                card.style.boxShadow = "none";
                card.style.border = "1px solid #e2e8f0";
            }
            if (header) {
                header.style.borderRadius = "0px";
            }
        }
    };

    html2canvas(element, options).then(canvas => {
        try {
            const link = document.createElement('a');
            const date = new Date().toISOString().slice(0,10);
            
            link.download = `Bukti-Pembayaran-${date}.png`;
            link.href = canvas.toDataURL("image/png", 1.0);
            link.click();
        } catch (err) {
            alert("Gagal mencetak bukti.");
        }
    });
}
</script>

<style>
    canvas {
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
    }
</style>
</x-app-layout>