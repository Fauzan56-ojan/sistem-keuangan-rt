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
            <form method="GET" class="mb-4">
                <select name="jenis" onchange="this.form.submit()">
                    <option value="all" {{ $jenis == 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="iuran" {{ $jenis == 'iuran' ? 'selected' : '' }}>Iuran</option>
                    <option value="pemasukan" {{ $jenis == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="pengeluaran" {{ $jenis == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                </select>

                <select name="bulan" onchange="this.form.submit()">
                    <option value="all" {{ $bulan == 'all' ? 'selected' : '' }}>
                        Semua Bulan
                    </option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>

                <select name="tahun" onchange="this.form.submit()">

                    @foreach ($tahunList as $th)
                        <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>
                            {{ $th }}
                        </option>
                    @endforeach

                </select>
            </form>

            <a href="{{ route('laporan.pdf', request()->all()) }}" target="_blank">
                Export PDF
            </a>
            <table class="w-full border border-gray-300">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Jenis</th>
                    <th class="p-2 border">Keterangan</th>

                    @if ($jenis != 'pengeluaran')
                        <th class="p-2 border">Masuk</th>
                    @endif

                    @if ($jenis != 'pemasukan' && $jenis != 'iuran')
                        <th class="p-2 border">Keluar</th>
                    @endif

                    @if ($jenis == 'all')
                        <th class="p-2 border">Saldo</th>
                    @endif
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

                    @if ($jenis != 'pengeluaran')
                        <td class="p-2 border text-green-600">
                            {{ $t['masuk'] ? 'Rp ' . number_format($t['masuk']) : '-' }}
                        </td>
                    @endif

                    @if ($jenis != 'pemasukan' && $jenis != 'iuran')
                        <td class="p-2 border text-red-600">
                            {{ $t['keluar'] ? 'Rp ' . number_format($t['keluar']) : '-' }}
                        </td>
                    @endif

                    @if ($jenis == 'all')
                        <td class="p-2 border font-bold">
                            Rp {{ number_format($t['saldo']) }}
                        </td>
                    @endif

                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="mt-4 font-bold">
                @if ($jenis == 'pengeluaran')
                    Total Pengeluaran: Rp {{ number_format($totalPengeluaran) }}
                @elseif ($jenis != 'all')
                    Total Pemasukan: Rp {{ number_format($totalPemasukan) }}
                @endif
            </div>
        </div>

    </div>
</x-app-layout>