<x-app-layout>
    <x-slot name="header">
        <h2>Tambah Pemasukan</h2>
    </x-slot>

    <div class="p-6">

        <form method="POST" action="/pemasukan" enctype="multipart/form-data">
            @csrf

            <input type="date" name="tanggal"><br>
            <input type="number" name="nominal"><br>
            <input type="text" name="keterangan"><br>
            <input type="file" name="bukti_file"><br>

            <button type="submit">Simpan</button>

        </form>

    </div>
</x-app-layout>