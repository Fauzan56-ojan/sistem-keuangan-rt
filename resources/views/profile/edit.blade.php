<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pengaturan Profil
        </h2>
    </x-slot>

    <main class="pt-12 pb-12 px-4 md:px-8 flex justify-center">
        <div class="w-full max-w-4xl space-y-8">

            <!-- HEADER -->
            <div class="relative pl-0 md:pl-0">
                <a href="{{ url('/settings') }}" 
                class="absolute -left-14 top-0.5 hidden md:inline-flex items-center justify-center w-10 h-10 bg-white hover:bg-gray-50 text-gray-600 hover:text-gray-900 rounded-xl border border-gray-200 shadow-sm transition-all group shrink-0" 
                title="Kembali">
                    <span class="material-symbols-outlined text-xl group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
                </a>

                <div class="flex flex-col space-y-1">
                    <div class="flex items-center gap-3">
                        <a href="{{ url('/settings') }}" class="inline-flex md:hidden items-center justify-center w-9 h-9 bg-white text-gray-600 rounded-lg border border-gray-200 shadow-sm">
                            <span class="material-symbols-outlined text-lg">arrow_back</span>
                        </a>
                        <h1 class="text-2xl font-bold text-on-surface">Pengaturan Profil</h1>
                    </div>
                    <p class="text-sm text-on-surface-variant pl-12 md:pl-0">
                        Perbarui detail identitas dan keamanan akun Anda
                    </p>
                </div>
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
                            <input type="text" value="{{ auth()->user()->nomor_rumah ?? '-' }}"
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
            <section id="password-section" class="bg-white rounded-2xl border border-outline-variant/30 shadow-sm">
                <div class="p-6 border-b flex items-center gap-3">
                    <span class="material-symbols-outlined text-orange-500">lock</span>
                    <h3 class="font-bold">Keamanan Password</h3>
                </div>

                <form method="post" action="{{ route('password.update') }}#password-section" class="p-8 space-y-5">
                    @csrf
                    @method('put')

                    <!-- Current -->
                    <div>
                        <label class="text-xs font-bold uppercase">Password Saat Ini</label>
                        <input name="current_password" type="password"
                            class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 focus:bg-white border border-transparent focus:border-primary text-sm">
                            @if($errors->updatePassword->has('current_password'))
                                <p class="text-red-500 text-sm mt-2">
                                    {{ $errors->updatePassword->first('current_password') }}
                                </p>
                            @endif
                    </div>

                    <!-- New -->
                    <div>
                        <label class="text-xs font-bold uppercase">Password Baru</label>
                        <input name="password" type="password"
                            class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 focus:bg-white border border-transparent focus:border-primary text-sm">
                            @if($errors->updatePassword->has('password'))
                                <p class="text-red-500 text-sm mt-2">
                                    {{ $errors->updatePassword->first('password') }}
                                </p>
                            @endif
                    </div>

                    <!-- Confirm -->
                    <div>
                        <label class="text-xs font-bold uppercase">Konfirmasi Password</label>
                        <input name="password_confirmation" type="password"
                            class="w-full mt-1 px-4 py-3 rounded-xl bg-gray-100 focus:bg-white border border-transparent focus:border-primary text-sm">
                            @if($errors->updatePassword->has('password_confirmation'))
                                <p class="text-red-500 text-sm mt-2">
                                    {{ $errors->updatePassword->first('password_confirmation') }}
                                </p>
                            @endif
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