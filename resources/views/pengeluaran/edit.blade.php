<x-app-layout>
    <x-slot name="header">
        <h2>Edit Pengeluaran</h2>
    </x-slot>

    <div class="p-6">

        <form method="POST" action="/pengeluaran/{{ $data->id }}">
            @csrf
            @method('PUT')

            <input type="date" name="tanggal" value="{{ $data->tanggal }}">
            <input type="number" name="nominal" value="{{ $data->nominal }}">
            <input type="text" name="keterangan" value="{{ $data->keterangan }}">

            <button type="submit">Update</button>

        </form>

    </div>
</x-app-layout>