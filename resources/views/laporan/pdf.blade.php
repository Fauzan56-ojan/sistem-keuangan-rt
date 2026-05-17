<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body{
            font-family: sans-serif;
            font-size: 12px;
            color: #222;
        }

        h2{
            margin-bottom: 4px;
        }

        .info{
            margin-bottom: 18px;
            line-height: 1.6;
        }

        .summary{
            margin-bottom: 18px;
        }

        .summary p{
            margin: 4px 0;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th{
            background: #f3f4f6;
        }

        th, td{
            border: 1px solid #d1d5db;
            padding: 8px;
            font-size: 11px;
        }

        .text-right{
            text-align: right;
        }

        .footer{
            margin-top: 16px;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <h2>Laporan Keuangan RT 01</h2>
    @if($jenis != 'all')
        <div class="info">
            <strong>Kategori :</strong>
            {{ ucfirst($jenis) }}
        </div>
    @endif

    <div class="info">
        <strong>Periode :</strong>
        @if($bulan == 'all' && $tahun == 'all')
            Semua Periode
        @elseif($bulan == 'all')
            {{ $tahun }}
        @elseif($tahun == 'all' && $bulan != 'all')
            Bulan {{ \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }}
        @else
            {{ \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }}
            {{ $tahun }}
        @endif
        <br>
        <strong>Dicetak :</strong>
        {{ now()->translatedFormat('d F Y') }}
    </div>

    <div class="summary">
        @if($jenis == 'all')
            <p>
                <strong>Total Pemasukan :</strong>
                Rp {{ number_format($totalPemasukan,0,',','.') }}
            </p>
            <p>
                <strong>Total Pengeluaran :</strong>
                Rp {{ number_format($totalPengeluaran,0,',','.') }}
            </p>
            <p>
                <strong>Saldo Akhir :</strong>
                Rp {{ number_format($saldo,0,',','.') }}
            </p>
        @elseif($jenis == 'pengeluaran')
            <p>
                <strong>Total Pengeluaran :</strong>
                Rp {{ number_format($totalPengeluaran,0,',','.') }}
            </p>
        @else
            <p>
                <strong>Total Pemasukan :</strong>
                Rp {{ number_format($totalPemasukan,0,',','.') }}
            </p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                @if($jenis == 'all')
                    <th>Jenis</th>
                @endif
                <th>Keterangan</th>
                @if($jenis != 'pengeluaran')
                    <th>Masuk</th>
                @endif
                @if($jenis == 'all' || $jenis == 'pengeluaran')
                    <th>Keluar</th>
                @endif
                @if($jenis == 'all')
                    <th>Saldo</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @foreach ($transaksi as $t)
            <tr>
                <td>
                    {{ \Carbon\Carbon::parse($t['tanggal'])->format('d-m-Y') }}
                </td>
                @if($jenis == 'all')
                    <td>
                        {{ ucfirst($t['jenis']) }}
                    </td>
                @endif
                <td>
                    {{ $t['keterangan'] }}
                </td>
                @if($jenis != 'pengeluaran')
                    <td class="text-right">
                        {{ $t['masuk'] ? 'Rp ' . number_format($t['masuk'],0,',','.') : '-' }}
                    </td>
                @endif
                @if($jenis == 'all' || $jenis == 'pengeluaran')
                    <td class="text-right">
                        {{ $t['keluar'] ? 'Rp ' . number_format($t['keluar'],0,',','.') : '-' }}
                    </td>
                @endif
                @if($jenis == 'all')
                    <td class="text-right">
                        Rp {{ number_format($t['saldo'],0,',','.') }}
                    </td>
                @endif
            </tr>
            @endforeach

        </tbody>
    </table>
    <div class="footer">
        Total Data : {{ count($transaksi) }} transaksi
    </div>
</body>
</html>