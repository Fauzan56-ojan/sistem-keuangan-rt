<x-app-layout>
    <x-slot name="header">
        <h2>Riwayat Pembayaran</h2>
    </x-slot>

    <div class="p-4">

        <form method="GET" class="mb-4">

            @if(in_array(auth()->user()->role, ['admin','bendahara']))
                <input 
                    type="text" 
                    name="search"
                    placeholder="Cari nama / nomor rumah..."
                    value="{{ request('search') }}"
                >
            @endif

            <select name="tahun">
                @foreach($tahunList as $th)
                    <option value="{{ $th }}" {{ request('tahun', now()->year) == $th ? 'selected' : '' }}>
                        {{ $th }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Filter</button>

        </form>

        <table border="1" cellpadding="5">
            <tr>
                <th>Tanggal</th>
                @if(auth()->user()->role != 'warga')
                    <th>Nama</th>
                @endif
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Nominal</th>
                @if(auth()->user()->role == 'warga')
                    <th>Aksi</th>
                @endif
            </tr>

            @foreach($data as $row)
            <tr>
                <td>
                    @if($row->status == 'success')
                        {{ \Carbon\Carbon::parse($row->paid_at)->format('d-m-Y H:i') }}
                    @else
                        {{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i') }}
                    @endif
                </td>
                @if(auth()->user()->role != 'warga')
                <td>
                    {{ $row->user->name }} <br>
                    <small>{{ $row->user->nomor_rumah }}</small>
                </td>
                @endif
                <td>
                    {{ \Carbon\Carbon::create()->month($row->iuran->periode_bulan)->translatedFormat('F') }}
                </td>
                <td>{{ $row->iuran->periode_tahun }}</td>
                <td>{{ $row->metode }}</td>
                <td>{{ $row->status }}</td>
                <td>Rp {{ number_format($row->amount) }}</td>
                @if(auth()->user()->role == 'warga')
                <td>
                    @if($row->status == 'pending')
                        
                        <a href="/checkout/{{ $row->iuran_id }}/{{ $row->metode }}">
                            Bayar
                        </a>

                        |

                        <form action="/pembayaran/{{ $row->id }}/batal" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Batal</button>
                        </form>

                    @else
                        -
                    @endif
                </td>
                @endif
            </tr>
            @endforeach

        </table>

    </div>
</x-app-layout>