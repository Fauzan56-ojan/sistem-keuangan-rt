<x-app-layout>
    <h2 class="text-xl font-bold mb-4">Iuran Warga</h2>

    <table class="w-full border">
        <thead>
            <tr>
                <th class="border p-2">Nama</th>
                <th class="border p-2">No Rumah</th>
                <th class="border p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="border p-2">{{ $user->name }}</td>
                    <td class="border p-2">{{ $user->nomor_rumah }}</td>
                    <td class="border p-2">
                        <a href="{{ url('/warga/' . $user->id . '/iuran') }}"
                           class="bg-blue-500 text-white px-3 py-1 rounded">
                            Lihat
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>