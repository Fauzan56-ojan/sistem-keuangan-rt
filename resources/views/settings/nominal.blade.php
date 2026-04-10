<x-app-layout>
    <x-slot name="header">
        <h2>Kelola Nominal</h2>
    </x-slot>

    <form action="{{ route('nominal.store') }}" method="POST">
        @csrf
        <input type="number" name="nominal" placeholder="Masukkan nominal" required>
        <button type="submit">Simpan</button>
    </form>

    <br>

    <table border="1" cellpadding="10">
        <tr>
            <th>Tahun</th>
            <th>Bulan</th>
            <th>Nominal</th>
        </tr>

        @foreach($data as $item)
        <tr>
            <td>{{ $item->tahun }}</td>
            <td>{{ $item->bulan }}</td>
            <td>{{ $item->nominal }}</td>
        </tr>
        @endforeach
    </table>
</x-app-layout>