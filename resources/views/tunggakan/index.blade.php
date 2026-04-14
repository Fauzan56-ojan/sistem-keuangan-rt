<x-app-layout>
    <x-slot name="header">
        <h2>Data Tunggakan</h2>
    </x-slot>

    <div class="p-4">

        <table class="w-full border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Nama</th>
                    <th class="p-2 border">Jumlah Bulan</th>
                    <th class="p-2 border">Total</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td class="p-2 border">{{ $item['nama'] }}</td>
                        <td class="p-2 border">{{ $item['jumlah_bulan'] }} bulan</td>
                        <td class="p-2 border">Rp {{ number_format($item['total']) }}</td>
                        <td class="p-2 border">
                            <a href="/tunggakan/{{ $item['user_id'] }}">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-2">
                            Tidak ada tunggakan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</x-app-layout>