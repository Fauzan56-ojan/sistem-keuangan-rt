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

        <table border="1">
            <tr>
                <th>Tanggal</th>
                <th>Nominal</th>
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