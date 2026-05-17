<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg">Pemasukan</h2>
    </x-slot>

    <div class="px-8 py-10 max-w-7xl mx-auto">

        <!-- HEADER -->
        <div x-data="{ open: false }" class="flex flex-col md:flex-row justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Pemasukan</h1>
                <p class="text-gray-500">Kelola dan pantau pemasukan kas</p>
            </div>

            @if(in_array(auth()->user()->role, ['admin','bendahara']))
                <button @click="open = true"
                    class="group inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 hover:shadow-lg hover:shadow-emerald-200 active:scale-95 text-white px-4 py-2 rounded-full text-sm font-semibold transition-all duration-200 ease-in-out">
                    
                    <span class="material-symbols-outlined text-[18px]">
                        add
                    </span>
                    
                    <span>Tambah Pemasukan</span>
                </button>
            @endif

            <!-- MODAL -->
            <div x-cloak x-show="open" x-transition
                class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

                <div @click.away="open = false"
                    class="bg-white w-full max-w-md rounded-xl shadow-lg p-6">

                    <!-- Judul -->
                    <div class="flex justify-between mb-4">
                        <h2 class="text-lg font-semibold">Tambah Pemasukan</h2>
                        <button @click="open = false">✕</button>
                    </div>

                    <!-- FORM -->
                    <form method="POST" action="/pemasukan" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-3">

                            <input type="date" name="tanggal" required
                            value="{{ date('Y-m-d') }}"
                                class="w-full border rounded px-3 py-2">

                            <input type="text" inputmode="numeric" name="nominal" required
                                placeholder="Nominal"
                                class="w-full border rounded px-3 py-2">

                            <input type="text" name="keterangan" required
                                placeholder="Keterangan"
                                class="w-full border rounded px-3 py-2">

                            <input type="file" name="bukti_file"
                                class="w-full text-sm">

                        </div>

                        <div class="mt-4 flex justify-end gap-2">
                            <button type="button" @click="open = false"
                                class="px-3 py-1 bg-gray-200 rounded text-sm">
                                Batal
                            </button>

                            <button type="submit"
                                class="px-3 py-1 bg-black text-white rounded text-sm">
                                Simpan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <!-- FILTER -->
        <form method="GET" class="bg-white p-5 rounded-xl shadow-sm mb-8 flex flex-wrap gap-4 items-center">

            <!-- Search -->
             <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    search
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari keterangan..."
                    class="pl-10 pr-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-200">
            </div>

            <input type="hidden" name="bulan" value="{{ request('bulan') }}">
            <input type="hidden" name="tahun" value="{{ request('tahun') }}">
            <input type="hidden" name="sort" value="{{ request('sort') }}">

            <button type="submit"
                class="px-4 py-2 bg-white text-black border border-black rounded-lg text-sm">
                Cari
            </button>
            
            <div class="relative">
                <button type="button" onclick="openFilterModal()"
                    class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm">
                    Filter & Urutkan
                </button>
            </div>

        </form> 
        

        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-4">
                                <a href="?bulan={{ request('bulan') }}&tahun={{ request('tahun') }}&sort_by=tanggal&order={{ request('order') == 'asc' ? 'desc' : 'asc' }}">
                                    Tanggal
                                </a>
                            </th>

                            <th class="px-6 py-4">Keterangan</th>

                            <th class="px-6 py-4">
                                <a href="?bulan={{ request('bulan') }}&tahun={{ request('tahun') }}&sort_by=nominal&order={{ request('order') == 'asc' ? 'desc' : 'asc' }}">
                                    Nominal
                                </a>
                            </th>

                            <th class="px-6 py-4 text-center">Bukti</th>
                            <th class="px-6 py-4">Dibuat Oleh</th>

                            @if(in_array(auth()->user()->role, ['admin','bendahara']))
                                <th class="px-6 py-4 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        @foreach($data as $item)

                        @php
                            $name = $item->user->name ?? 'User';
                            $words = explode(' ', $name);
                            $initials = strtoupper(substr($words[0],0,1) . (isset($words[1]) ? substr($words[1],0,1) : ''));
                        @endphp

                        <tr x-data="{ openEdit: false }" class="hover:bg-gray-50 group transition">

                            <!-- Tanggal -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-semibold">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                    </span>
                                </div>
                            </td>

                            <!-- Keterangan -->
                            <td class="px-6 py-4">
                                {{ $item->keterangan }}
                            </td>

                            <!-- Nominal -->
                            <td class="px-6 py-4 text-emerald-600 font-bold">
                                Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>

                            <!-- Bukti -->
                            <td class="px-6 py-4 text-center">
                                @if($item->bukti_file)
                                    <a href="{{ asset('storage/' . $item->bukti_file) }}" target="_blank"
                                       class="text-emerald-600 hover:underline text-xs">
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>

                            <!-- User -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">
                                        {{ $initials }}
                                    </div>
                                    <span class="text-xs font-semibold">
                                        {{ $name }}
                                    </span>
                                </div>
                            </td>

                            <!-- Aksi -->
                            @if(in_array(auth()->user()->role, ['admin','bendahara']))
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">

                                    <button @click="openEdit = true"
                                        class="text-gray-500 hover:text-emerald-600">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>

                                    <x-delete-modal action="/pemasukan/{{ $item->id }}" />

                                </div>
                                <!-- MODAL EDIT -->
                                <div x-cloak x-show="openEdit" x-transition
                                    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

                                    <div @click.away="openEdit = false"
                                        class="bg-white w-full max-w-md rounded-xl shadow-lg p-6">

                                        <h2 class="text-lg font-semibold mb-4">Edit Pemasukan</h2>

                                        <form method="POST" action="/pemasukan/{{ $item->id }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="space-y-3">

                                                <input type="date" name="tanggal" required
                                                    value="{{ $item->tanggal }}"
                                                    class="w-full border rounded px-3 py-2">

                                                <input type="text" inputmode="numeric" name="nominal" required
                                                    value="{{ number_format($item->nominal, 0, ',', '.') }}"
                                                    class="w-full border rounded px-3 py-2 nominal-input">

                                                <input type="text" name="keterangan" required
                                                    value="{{ $item->keterangan }}"
                                                    class="w-full border rounded px-3 py-2">

                                                <input type="file" name="bukti_file"
                                                    class="w-full text-sm">

                                            </div>

                                            <div class="mt-4 flex justify-end gap-2">
                                                <button type="button" @click="openEdit = false"
                                                    class="px-3 py-1 bg-gray-200 rounded text-sm">
                                                    Batal
                                                </button>

                                                <button type="submit"
                                                    class="px-3 py-1 bg-black text-white rounded text-sm">
                                                    Update
                                                </button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </td>
                            @endif

                        </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>
        </div>

    </div>
    <x-filter-modal :tahunList="$tahunList" />
<script>
    document.querySelectorAll('input[name="nominal"]').forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = new Intl.NumberFormat('id-ID').format(value);
        });
    });

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            form.querySelectorAll('input[name="nominal"]').forEach(input => {
                input.value = input.value.replace(/\./g, '');
            });
        }); 
    });
</script>
</x-app-layout>