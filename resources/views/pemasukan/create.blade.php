<x-app-layout>
    <x-slot name="header">
        <h2>Tambah Pemasukan</h2>
    </x-slot>

    <div class="p-6">

        <form method="POST" action="/pemasukan">
            @csrf

            <input type="date" name="tanggal">
            <input type="number" name="nominal">
            <input type="text" name="keterangan">

            <button type="submit">Simpan</button>

        </form>

    </div>
</x-app-layout>