<h2>Laporan Keuangan</h2>

<p>Total Pemasukan: Rp {{ number_format($totalPemasukan) }}</p>
<p>Total Pengeluaran: Rp {{ number_format($totalPengeluaran) }}</p>
<p>Saldo: Rp {{ number_format($saldo) }}</p>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Keterangan</th>

            @if ($jenis != 'pengeluaran')
                <th>Masuk</th>
            @endif

            @if ($jenis != 'pemasukan' && $jenis != 'iuran')
                <th>Keluar</th>
            @endif

            @if ($jenis == 'all')
                <th>Saldo</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($transaksi as $t)
        <tr>
            <td>{{ \Carbon\Carbon::parse($t['tanggal'])->format('d-m-Y') }}</td>

            <td>{{ $t['jenis'] }}</td>

            <td>{{ $t['keterangan'] }}</td>

            @if ($jenis != 'pengeluaran')
                <td>
                    {{ $t['masuk'] ? 'Rp ' . number_format($t['masuk']) : '-' }}
                </td>
            @endif

            @if ($jenis != 'pemasukan' && $jenis != 'iuran')
                <td>
                    {{ $t['keluar'] ? 'Rp ' . number_format($t['keluar']) : '-' }}
                </td>
            @endif

            @if ($jenis == 'all')
                <td>
                    Rp {{ number_format($t['saldo']) }}
                </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>

@if ($jenis == 'pengeluaran')
    <p>Total Pengeluaran: Rp {{ number_format($totalPengeluaran) }}</p>
@elseif ($jenis != 'all')
    <p>Total Pemasukan: Rp {{ number_format($totalPemasukan) }}</p>
@endif