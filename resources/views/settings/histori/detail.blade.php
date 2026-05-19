<x-app-layout>

    <div class="p-6">

        <h1 class="text-2xl font-bold mb-6">
            {{ $warga->name }}
        </h1>

        <div class="mb-6">

    <form method="GET">

        <div class="flex items-center gap-3">

            <span class="font-semibold">
                Tahun Histori
            </span>

            <select
                name="tahun"
                onchange="this.form.submit()"
                class="border rounded-lg px-3 py-2">

                @foreach($tahunList as $th)

                    <option
                        value="{{ $th }}"
                        {{ $tahun == $th ? 'selected' : '' }}>

                        {{ $th }}

                    </option>

                @endforeach

            </select>

        </div>

    </form>

</div>

        <div class="mb-6">

            <button
                onclick="document.getElementById('modalTambah').classList.remove('hidden')"
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-semibold">

                Tambah Data Histori

            </button>

        </div>

        <div class="space-y-3">

            @forelse($iuran as $row)

                <div class="border p-3 rounded">

                    <div class="font-semibold">
                        {{ \Carbon\Carbon::create()->month($row->periode_bulan)->translatedFormat('F') }}
                    </div>

                    <div class="text-sm text-gray-500">
                        Rp {{ number_format($row->nominal,0,',','.') }}
                    </div>

                </div>

            @empty

                <div class="text-gray-500">
                    Belum ada histori pembayaran
                </div>

            @endforelse

        </div>

    </div>

    <!-- Modal -->
    <div id="modalTambah"
         class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

        <form method="POST"
              action="{{ route('settings.histori.store', $warga->id) }}"
              class="bg-white rounded-2xl w-full max-w-5xl p-6">

            @csrf

            <input type="hidden" name="tahun" value="{{ $tahun }}">

            <div class="flex items-center justify-between mb-6">

                <h2 class="text-xl font-bold">
                    Tambah Histori Pembayaran
                </h2>

                <button type="button"
                    onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="text-gray-500">
                    ✕
                </button>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full border-collapse">

                    <thead>

                        <tr class="bg-gray-100">

                            <th class="p-3 text-left">Bulan</th>
                            <th class="p-3 text-left">Buat Data</th>
                            <th class="p-3 text-left">Tanggal Bayar</th>
                            <th class="p-3 text-left">Nominal</th>

                        </tr>

                    </thead>

                    <tbody>

                        @for($i = 1; $i <= 12; $i++)

                            <tr class="border-b">

                                <td class="p-3">

                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}

                                </td>

                                <td class="p-3">

                                    <input type="checkbox"
                                           name="bulan[{{ $i }}]"
                                           value="1">

                                </td>

                                <td class="p-3">

                                    <input type="date"
                                           name="tanggal[{{ $i }}]"
                                           class="border rounded px-3 py-2 w-full">

                                </td>

                                <td class="p-3">

                                    <input type="number"
                                           name="nominal[{{ $i }}]"
                                           placeholder="Nominal"
                                           class="border rounded px-3 py-2 w-full">

                                </td>

                            </tr>

                        @endfor

                    </tbody>

                </table>

            </div>

            <div class="mt-6 flex justify-end gap-3">

                <button
                    type="button"
                    onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="px-4 py-2 border rounded-lg">

                    Batal

                </button>

                <button
                    type="submit"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg">

                    Simpan

                </button>

            </div>

        </form>

    </div>

</x-app-layout>