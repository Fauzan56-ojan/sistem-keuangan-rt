<x-app-layout>

    <div class="p-6">

        <h1 class="text-2xl font-bold mb-6">
            Histori Pembayaran Lama
        </h1>

        <details class="mb-4">
            <summary class="cursor-pointer font-bold">
                Warga Aktif
            </summary>

            <div class="mt-3 space-y-2">

                @foreach($aktif as $user)

                    <div class="border p-3 rounded">
                        <a href="{{ route('settings.histori.detail', $user->id) }}"
                            class="text-blue-600 font-semibold">
                                {{ $user->name }}
                            </a>
                    </div>

                @endforeach

            </div>
        </details>

        <details>
            <summary class="cursor-pointer font-bold">
                Warga Tidak Aktif
            </summary>

            <div class="mt-4 mb-4">

                <button
                    onclick="document.getElementById('modalNonaktif').classList.remove('hidden')"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold">

                    Tambah Warga Nonaktif

                </button>

            </div>

            <div class="mt-3 space-y-2">

                @foreach($nonaktif as $user)

                    <div class="border p-3 rounded">
                        <a href="{{ route('settings.histori.detail', $user->id) }}"
                        class="text-blue-600 font-semibold">
                            {{ $user->name }}
                        </a>
                    </div>

                @endforeach

            </div>
        </details>

    </div>

    <div id="modalNonaktif"
     class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">

    <form method="POST"
          action="{{ route('settings.histori.nonaktif.store') }}"
          class="bg-white rounded-2xl w-full max-w-lg p-6">

        @csrf

        <div class="flex items-center justify-between mb-6">

            <h2 class="text-xl font-bold">
                Tambah Warga Nonaktif
            </h2>

            <button
                type="button"
                onclick="document.getElementById('modalNonaktif').classList.add('hidden')"
                class="text-gray-500">

                ✕

            </button>

        </div>

        <div class="space-y-4">

            <div>

                <label class="block mb-2 font-semibold">
                    Nama Warga
                </label>

                <input type="text"
                       name="name"
                       required
                       class="w-full border rounded-lg px-3 py-2">

            </div>

            <div>

                <label class="block mb-2 font-semibold">
                    Nomor Rumah
                </label>

                <input type="text"
                       name="nomor_rumah"
                       class="w-full border rounded-lg px-3 py-2">

            </div>

        </div>

        <div class="mt-6 flex justify-end gap-3">

            <button
                type="button"
                onclick="document.getElementById('modalNonaktif').classList.add('hidden')"
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