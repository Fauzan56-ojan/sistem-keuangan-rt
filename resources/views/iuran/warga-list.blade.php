<x-app-layout>
    <h2 class="text-xl font-bold mb-4">Iuran Warga</h2>

    <form method="GET" class="mb-4">
        <input 
            type="text" 
            name="search" 
            placeholder="Cari nama / nomor rumah..."
            value="{{ request('search') }}"
            class="border px-2 py-1 rounded"
        >

        <button class="bg-gray-500 text-white px-3 py-1 rounded">
            Cari
        </button>
    </form>

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