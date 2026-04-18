<x-app-layout>
    <x-slot name="header">
        <h2>Detail Tunggakan</h2>
    </x-slot>

    <div class="p-4">

        <table class="w-full border">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Nominal</th>
                    @if(auth()->user()->role !== 'ketua_rt')
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @forelse ($iuran as $row)
                    <tr>
                        <td>
                            {{ \Carbon\Carbon::create()->month($row->periode_bulan)->translatedFormat('F') }}
                        </td>
                        <td>{{ $row->periode_tahun }}</td>
                        <td>Rp {{ number_format($row->nominal) }}</td>

                        <td>

                            @if(in_array(auth()->user()->role, ['admin','bendahara']))
                                <a href="/checkout/{{ $row->id }}/tunai">Tunai</a>
                                |
                            @endif

                            @if(in_array(auth()->user()->role, ['admin','bendahara','warga']))
                                <a href="/checkout/{{ $row->id }}/online">Online</a>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Tidak ada tunggakan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</x-app-layout>