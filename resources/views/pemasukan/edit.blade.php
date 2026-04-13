<x-app-layout>
    <x-slot name="header">
        <h2>Edit Pemasukan</h2>
    </x-slot>

    <div class="p-6">

        <form method="POST" action="/pemasukan/{{ $data->id }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                Tanggal
                <input type="date" name="tanggal" value="{{ $data->tanggal }}">
            </div>

            <div>
                Nominal
                <input type="number" name="nominal" value="{{ $data->nominal }}">
            </div>

            <div>
                Keterangan
                <input type="text" name="keterangan" value="{{ $data->keterangan }}">
            </div>

            <div>
                Bukti File
                <input type="file" name="bukti_file">
            </div>

            <button type="submit">Update</button>

        </form>

    </div>
</x-app-layout>