<x-app-layout>
    <x-slot name="header">
        <h2>Pemasukan</h2>
    </x-slot>

    <div class="p-6">
        @if(in_array(auth()->user()->role, ['admin','bendahara']))
            <a href="/pemasukan/create" class="bg-blue-500 text-white px-3 py-1 rounded">
                + Tambah
            </a>
        @endif

        <br><br>
        <form method="GET">

            {{-- BULAN --}}
            <select name="bulan">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('bulan', now()->month) == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            {{-- TAHUN --}}
            <select name="tahun">
                @foreach($tahunList as $th)
                    <option value="{{ $th }}" {{ request('tahun', now()->year) == $th ? 'selected' : '' }}>
                        {{ $th }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Filter</button>

        </form>
        <table border="1">
            <tr>
                <th>
                    <a href="?bulan={{ request('bulan') }}&tahun={{ request('tahun') }}&sort_by=tanggal&order={{ request('order') == 'asc' ? 'desc' : 'asc' }}">
                        Tanggal
                        @if(request('sort_by') == 'tanggal')
                            {{ request('order') == 'asc' ? '↑' : '↓' }}
                        @endif
                    </a>
                </th>
                <th>
                    <a href="?bulan={{ request('bulan') }}&tahun={{ request('tahun') }}&sort_by=nominal&order={{ request('order') == 'asc' ? 'desc' : 'asc' }}">
                        Nominal
                        @if(request('sort_by') == 'nominal')
                            {{ request('order') == 'asc' ? '↑' : '↓' }}
                        @endif
                    </a>
                </th>
                <th>Keterangan</th>
                @if(in_array(auth()->user()->role, ['admin','bendahara']))
                    <th>Aksi</th>
                @endif
                <th>Bukti File</th>
            </tr>

            @foreach($data as $item)
            <tr>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->nominal }}</td>
                <td>{{ $item->keterangan }}</td>

                @if(in_array(auth()->user()->role, ['admin','bendahara']))
                <td>
                    <a href="/pemasukan/{{ $item->id }}/edit">Edit</a>

                    <form action="/pemasukan/{{ $item->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </td>
                @endif

                <td>
                    @if ($item->bukti_file)
                        <a href="{{ asset('storage/' . $item->bukti_file) }}" target="_blank">
                            Lihat
                        </a>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach

        </table>

    </div>
</x-app-layout>