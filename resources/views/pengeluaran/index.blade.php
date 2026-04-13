<x-app-layout>
    <x-slot name="header">
        <h2>Pengeluaran</h2>
    </x-slot>

    <div class="p-6">

        <a href="/pengeluaran/create">+ Tambah</a>

        <table border="1">
            <tr>
                <th>Tanggal</th>
                <th>Nominal</th>
                <th>Keterangan</th>
                <th>Aksi</th>
                <th>Bukti File</th>
            </tr>

            @foreach($data as $item)
            <tr>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->nominal }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>
                    <a href="/pengeluaran/{{ $item->id }}/edit">Edit</a>

                    <form action="/pengeluaran/{{ $item->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </td>
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