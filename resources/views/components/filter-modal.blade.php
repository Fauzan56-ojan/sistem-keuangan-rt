<div id="filter-modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white w-[400px] rounded-2xl shadow-lg overflow-hidden">

        <!-- TAB -->
        <div class="flex border-b text-sm font-semibold">
            <button type="button" onclick="switchTab('filter')" id="tab-filter"
                class="w-1/2 py-3 border-b-2 border-emerald-500 text-emerald-600">
                Filter
            </button>

            <button type="button" onclick="switchTab('sort')" id="tab-sort"
                class="w-1/2 py-3 border-b-2 border-emerald-500 text-emerald-600">
                Urutkan
            </button>
        </div>

        <form method="GET" class="p-5 space-y-4">

            <input type="hidden" name="search" value="{{ request('search') }}">

            <!-- FILTER -->
            <div id="content-filter">
                <select name="bulan" class="w-full border px-3 py-2 rounded-lg text-sm">
                    <option value="all">Semua Bulan</option>
                    @for($i=1;$i<=12;$i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>

                <select name="tahun" class="w-full border px-3 py-2 rounded-lg text-sm mt-3">

                    <option value="all"
                        {{ request('tahun', now()->year) == 'all' ? 'selected' : '' }}>
                        Semua Tahun
                    </option>

                    @foreach($tahunList as $th)
                        <option value="{{ $th }}"
                            {{ request('tahun', now()->year) == $th ? 'selected' : '' }}>
                            {{ $th }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- SORT -->
            <div id="content-sort" class="space-y-4 hidden">

                <label class="flex gap-2 text-sm">
                    <input type="radio" name="sort" value="tanggal_desc"
                        {{ request('sort','tanggal_desc') == 'tanggal_desc' ? 'checked' : '' }}>
                    Tanggal Terbaru
                </label>

                <label class="flex gap-2 text-sm">
                    <input type="radio" name="sort" value="tanggal_asc"
                        {{ request('sort') == 'tanggal_asc' ? 'checked' : '' }}>
                    Tanggal Terlama
                </label>

                @if($showNominal ?? true)
                <label class="flex gap-2 text-sm">
                    <input type="radio" name="sort" value="nominal_desc"
                        {{ request('sort') == 'nominal_desc' ? 'checked' : '' }}>
                    Nominal Terbesar
                </label>

                <label class="flex gap-2 text-sm">
                    <input type="radio" name="sort" value="nominal_asc"
                        {{ request('sort') == 'nominal_asc' ? 'checked' : '' }}>
                    Nominal Terkecil
                </label>
                @endif
            </div>

            <div class="flex justify-end gap-2 pt-3">
                <button type="button" onclick="closeFilterModal()"
                    class="px-4 py-2 text-sm bg-gray-200 rounded-lg">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 text-sm bg-black text-white rounded-lg">
                    Terapkan
                </button>
            </div>
        </form>
    </div>
</div>