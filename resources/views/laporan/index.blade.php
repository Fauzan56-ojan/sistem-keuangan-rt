<x-app-layout>
    <x-slot name="header">
        <h2>Laporan Keuangan</h2>
    </x-slot>

    <div class="p-4 space-y-6">

        <div>
            <p>Total Pemasukan: Rp {{ number_format($totalPemasukan) }}</p>
            <p>Total Pengeluaran: Rp {{ number_format($totalPengeluaran) }}</p>
            <p>Saldo: Rp {{ number_format($saldo) }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">Tanggal</th>
                        <th class="p-2 border">Jenis</th>
                        <th class="p-2 border">Keterangan</th>
                        <th class="p-2 border">Masuk</th>
                        <th class="p-2 border">Keluar</th>
                        <th class="p-2 border">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi as $t)
                        <tr>
                            <td class="p-2 border">
                                {{ \Carbon\Carbon::parse($t['tanggal'])->format('d-m-Y') }}
                            </td>
                            <td class="p-2 border capitalize">
                                {{ $t['jenis'] }}
                            </td>
                            <td class="p-2 border">
                                {{ $t['keterangan'] }}
                            </td>
                            <td class="p-2 border text-green-600">
                                {{ $t['masuk'] ? 'Rp ' . number_format($t['masuk']) : '-' }}
                            </td>
                            <td class="p-2 border text-red-600">
                                {{ $t['keluar'] ? 'Rp ' . number_format($t['keluar']) : '-' }}
                            </td>
                            <td class="p-2 border font-bold">
                                Rp {{ number_format($t['saldo']) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>