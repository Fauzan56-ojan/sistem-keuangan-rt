<x-app-layout>
    <x-slot name="header">
        <h2>Tambah Pengeluaran</h2>
    </x-slot>

    <div class="p-6">

        <form method="POST" action="/pengeluaran" enctype="multipart/form-data">
            @csrf

            <input type="date" name="tanggal">
            <input type="number" name="nominal">
            <input type="text" name="keterangan">
            <input type="file" name="bukti_file">

            <button type="submit">Simpan</button>

        </form>

    </div>
</x-app-layout>