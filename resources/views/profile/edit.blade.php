<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pengaturan Profil
        </h2>
    </x-slot>

    <main class="pt-12 pb-12 px-4 md:px-8 flex justify-center">
        <div class="w-full max-w-4xl space-y-8">

            <!-- HEADER -->
            <div>
                <h1 class="text-2xl font-bold text-on-surface">Pengaturan Profil</h1>
                <p class="text-sm text-on-surface-variant mt-1">
                    Perbarui detail identitas dan keamanan akun Anda
                </p>
            </div>
            <section class="bg-white rounded-2xl border border-outline-variant/30 shadow-sm">
                <div class="p-6 border-b flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">person</span>
                    <h3 class="font-bold">Informasi Umum</h3>
                </div>

                <form method="post" action="{{ route('profile.update') }}" class="p-8">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Nama -->
                        <div>
                            <label class="text-xs font-bold uppercase">Nama</label>
                            <input name="name" type="text"
                                value="{{ old('name', auth()->user()->name) }}"
                                class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 focus:bg-white border border-transparent focus:border-primary text-sm">
                        </div>

                        <!-- Username (readonly) -->
                        <div>
                            <label class="text-xs font-bold uppercase">Username</label>
                            <input type="text" value="{{ auth()->user()->username ?? '-' }}"
                                class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 text-gray-400 text-sm" readonly>
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label class="text-xs font-bold uppercase">Alamat</label>
                            <input type="text" value="{{ auth()->user()->alamat ?? '-' }}"
                                class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 text-gray-400 text-sm" readonly>
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label class="text-xs font-bold uppercase">Nomor Telepon</label>
                            <input name="telp" type="text"
                                value="{{ old('telp', auth()->user()->telp) }}"
                                class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 focus:bg-white border border-transparent focus:border-primary text-sm">
                        </div>

                    </div>

                    <div class="flex justify-end mt-8">
                        <button class="w-full sm:w-auto bg-blue-600 text-white px-6 py-3 rounded-xl text-sm font-bold">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </section>

            <!-- ===================== -->
            <!-- PASSWORD -->
            <!-- ===================== -->
            <section class="bg-white rounded-2xl border border-outline-variant/30 shadow-sm">
                <div class="p-6 border-b flex items-center gap-3">
                    <span class="material-symbols-outlined text-orange-500">lock</span>
                    <h3 class="font-bold">Keamanan Password</h3>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="p-8 space-y-5">
                    @csrf
                    @method('put')

                    <!-- Current -->
                    <div>
                        <label class="text-xs font-bold uppercase">Password Saat Ini</label>
                        <input name="current_password" type="password"
                            class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 focus:bg-white border border-transparent focus:border-primary text-sm">
                    </div>

                    <!-- New -->
                    <div>
                        <label class="text-xs font-bold uppercase">Password Baru</label>
                        <input name="password" type="password"
                            class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 focus:bg-white border border-transparent focus:border-primary text-sm">
                    </div>

                    <!-- Confirm -->
                    <div>
                        <label class="text-xs font-bold uppercase">Konfirmasi Password</label>
                        <input name="password_confirmation" type="password"
                            class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 focus:bg-white border border-transparent focus:border-primary text-sm">
                    </div>

                    <div class="flex justify-end">
                        <button class="w-full sm:w-auto border px-6 py-3 rounded-xl text-sm font-bold">
                            Update Password
                        </button>
                    </div>
                </form>
            </section>

        </div>
    </main>
</x-app-layout>