@props([
    'action'
])

<div x-data="{ openDelete: false }" class="flex items-center">

    <!-- BUTTON -->
    <button
        @click="openDelete = true"
        class="p-2 text-gray-500 hover:text-red-600">

        <span class="material-symbols-outlined text-[18px]">
            delete
        </span>
    </button>

    <!-- MODAL -->
    <div
        x-cloak
        x-show="openDelete"
        x-transition
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">

        <div
            @click.away="openDelete = false"
            class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl">

            <div class="flex items-center gap-3 mb-4">

                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                    <span class="material-symbols-outlined">
                        warning
                    </span>
                </div>

                <div>
                    <h3 class="font-bold text-gray-800">
                        Hapus Data
                    </h3>

                    <p class="text-sm text-gray-500">
                        Data yang dihapus tidak dapat dikembalikan.
                    </p>
                </div>

            </div>

            <div class="flex justify-end gap-2">

                <button
                    @click="openDelete = false"
                    class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm">

                    Batal
                </button>

                <form action="{{ $action }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button
                        class="px-4 py-2 rounded-lg bg-red-500 text-white text-sm hover:bg-red-600">

                        Hapus
                    </button>
                </form>

            </div>

        </div>

    </div>

</div>