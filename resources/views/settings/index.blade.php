<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Setting</h2>
    </x-slot>

    <div class="p-6">

        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-800">Settings</h1>
            <p class="text-gray-500 mt-1">Pengaturan sistem</p>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Profile -->
            <a href="{{ route('settings.profile') }}"
               class="group flex items-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition hover:-translate-y-1">

                <div class="w-14 h-14 flex items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition">
                    <span class="material-symbols-outlined text-2xl">person</span>
                </div>

                <div class="ml-4 flex-1">
                    <h3 class="font-semibold text-lg text-gray-800 group-hover:text-emerald-600">
                        Profile
                    </h3>
                    <p class="text-sm text-gray-500">
                        Kelola informasi akun
                    </p>
                </div>

                <span class="material-symbols-outlined text-gray-300 group-hover:translate-x-1 transition">
                    chevron_right
                </span>
            </a>

            <!-- Migrasi -->
            <a href="{{ route('settings.migrasi') }}"
               class="group flex items-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition hover:-translate-y-1">

                <div class="w-14 h-14 flex items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition">
                    <span class="material-symbols-outlined text-2xl">database</span>
                </div>

                <div class="ml-4 flex-1">
                    <h3 class="font-semibold text-lg text-gray-800 group-hover:text-emerald-600">
                        Migrasi
                    </h3>
                    <p class="text-sm text-gray-500">
                        Manajemen data sistem
                    </p>
                </div>

                <span class="material-symbols-outlined text-gray-300 group-hover:translate-x-1 transition">
                    chevron_right
                </span>
            </a>

            <!-- Kelola Iuran -->
            <a href="{{ route('settings.nominal') }}"
               class="group flex items-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition hover:-translate-y-1">

                <div class="w-14 h-14 flex items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition">
                    <span class="material-symbols-outlined text-2xl">payments</span>
                </div>

                <div class="ml-4 flex-1">
                    <h3 class="font-semibold text-lg text-gray-800 group-hover:text-emerald-600">
                        Kelola Iuran
                    </h3>
                    <p class="text-sm text-gray-500">
                        Atur nominal iuran
                    </p>
                </div>

                <span class="material-symbols-outlined text-gray-300 group-hover:translate-x-1 transition">
                    chevron_right
                </span>
            </a>

            <!-- Generate Iuran -->
            <a href="{{ route('settings.generate') }}"
               class="group flex items-center p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition hover:-translate-y-1">

                <div class="w-14 h-14 flex items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition">
                    <span class="material-symbols-outlined text-2xl">auto_fix_high</span>
                </div>

                <div class="ml-4 flex-1">
                    <h3 class="font-semibold text-lg text-gray-800 group-hover:text-emerald-600">
                        Generate Iuran
                    </h3>
                    <p class="text-sm text-gray-500">
                        Buat iuran otomatis
                    </p>
                </div>

                <span class="material-symbols-outlined text-gray-300 group-hover:translate-x-1 transition">
                    chevron_right
                </span>
            </a>

        </div>

    </div>
</x-app-layout>