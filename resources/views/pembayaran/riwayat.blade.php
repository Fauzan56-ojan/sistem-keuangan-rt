<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg">Riwayat Pembayaran</h2>
    </x-slot>

    <div class="px-8 py-10 max-w-7xl mx-auto">

        <!-- HEADER -->
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-800">Riwayat Pembayaran</h1>
            <p class="text-gray-500">Daftar semua transaksi pembayaran iuran</p>
        </div>

        @php
            $total = count($data);
            $success = collect($data)->where('status','success')->sum('amount');
            $pending = collect($data)->where('status','pending')->sum('amount');
        @endphp

        <!-- FILTER -->
        <form method="GET" class="bg-white p-5 rounded-xl shadow-sm mb-8 flex flex-wrap gap-4 items-center">

        <!-- SEARCH -->
        @if(in_array(auth()->user()->role, ['admin','bendahara']))
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                search
            </span>
            <input type="text" name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau alamat..."
                class="pl-10 pr-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-200">
        </div>
        @endif

        <!-- biar state filter tetap kebawa -->
        <input type="hidden" name="bulan" value="{{ request('bulan') }}">
        <input type="hidden" name="tahun" value="{{ request('tahun') }}">
        <input type="hidden" name="sort" value="{{ request('sort') }}">

        @if(in_array(auth()->user()->role, ['admin','bendahara']))
        <!-- tombol cari -->
        <button type="submit"
            class="px-4 py-2 bg-emerald-500 text-white rounded-lg text-sm">
            Cari
        </button>
        @endif

        <!-- tombol buka modal -->
        <button type="button" onclick="openFilterModal()"
            class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm">
            Filter & Urutkan
        </button>

    </form>

        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>

                            @if(auth()->user()->role != 'warga')
                                <th class="px-6 py-4">User</th>
                            @endif

                            <th class="px-6 py-4">Periode</th>
                            <th class="px-6 py-4">Metode</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Nominal</th>

                            @if(auth()->user()->role == 'warga')
                                <th class="px-6 py-4 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        @foreach($data as $row)

                        @php
                            $name = $row->user->name;
                            $words = explode(' ', $name);
                            $initials = strtoupper(substr($words[0],0,1) . (isset($words[1]) ? substr($words[1],0,1) : ''));

                            $tanggal = $row->status == 'success'
                                ? $row->paid_at
                                : $row->created_at;
                        @endphp

                        <tr class="hover:bg-gray-50 transition">

                            <!-- Tanggal -->
                            <td class="px-6 py-4 text-gray-500">
                                {{ \Carbon\Carbon::parse($tanggal)->format('d M Y H:i') }}
                            </td>

                            <!-- User -->
                            @if(auth()->user()->role != 'warga')
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold">{{ $name }}</p>
                                        <p class="text-xs text-gray-400">
                                            {{ $row->user->nomor_rumah }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            @endif

                            <!-- Periode -->
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::create()->month($row->iuran->periode_bulan)->translatedFormat('F') }}
                                {{ $row->iuran->periode_tahun }}
                            </td>

                            <!-- Metode -->
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100">
                                    {{ strtoupper($row->metode) }}
                                </span>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                @if($row->status == 'success')
                                    <span class="text-emerald-600 text-xs font-semibold">● Success</span>
                                @else
                                    <span class="text-amber-500 text-xs font-semibold">● Pending</span>
                                @endif
                            </td>

                            <!-- Nominal -->
                            <td class="px-6 py-4 text-right font-bold text-emerald-600">
                                Rp {{ number_format($row->amount,0,',','.') }}
                            </td>

                            <!-- Aksi -->
                            @if(auth()->user()->role == 'warga')
                            <td class="px-6 py-4 text-center">

                                @if($row->status == 'pending')

                                    <div class="flex items-center justify-center gap-2 text-xs">

                                        <a href="/checkout/{{ $row->iuran_id }}/{{ $row->metode }}"
                                            class="px-3 py-1 rounded-md bg-emerald-500 text-white font-medium hover:bg-emerald-600 transition">
                                            Bayar
                                        </a>

                                        <form action="/pembayaran/{{ $row->id }}/batal" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="px-3 py-1 rounded-md bg-red-100 text-red-600 font-medium hover:bg-red-200 transition">
                                                Batalkan
                                            </button>
                                        </form>

                                    </div>

                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif

                            </td>
                            @endif

                        </tr>

                        @endforeach

                    </tbody>

                </table>
            </div>

             <!-- PAGINATION -->
            <div class="px-6 py-4 border-t bg-white">
                {{ $data->links() }}
            </div>
            
        </div>

    </div>
    <x-filter-modal :tahunList="$tahunList" :showNominal="false" />
</x-app-layout>