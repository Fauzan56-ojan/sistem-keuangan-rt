<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Data User</h2>
    </x-slot>

    <div class="p-6">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-extrabold text-gray-800">Kelola User</h1>
            <p class="text-gray-500 mt-1">Manajemen pengguna sistem</p>
        </div>

        <!-- Card -->
        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">

            <!-- Actions -->
            <div x-data="{ open: false }" class="p-5 flex flex-col sm:flex-row justify-between gap-4">

                <!-- Search (optional, belum aktif backend) -->
                <div class="relative w-full sm:w-80">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        search
                    </span>
                    <input type="text"
                        placeholder="Cari user..."
                        class="w-full pl-10 pr-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-200">
                </div>

                <!-- Button -->
                <button @click="open = true"
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-semibold flex items-center gap-2 hover:bg-emerald-700 transition">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Tambah
                </button>

                <!-- MODAL TAMBAH USER -->
                <div x-cloak x-show="open" x-transition 
                        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                        
                        <div @click.away="open = false" 
                            class="bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden flex flex-col">
                            
                            <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-white">
                                <h3 class="text-xl font-bold tracking-tight text-black">Tambah User</h3>
                                <button @click="open = false" class="text-slate-400 hover:text-black transition-colors">
                                    <span class="material-symbols-outlined">close</span>
                                </button>
                            </div>

                            <form method="POST" action="/users">
                                @csrf
                                
                                <div class="px-8 py-6 space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-black uppercase tracking-widest mb-1.5 ml-1">Nama Lengkap</label>
                                        <div class="relative">
                                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-black text-lg">person</span>
                                            <input name="name" type="text" required
                                                class="w-full pl-12 pr-4 py-2.5 bg-slate-50 border-none rounded-lg text-black focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-300 text-sm" 
                                                placeholder="Contoh: Budi Santoso" />
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-black uppercase tracking-widest mb-1.5 ml-1">Username</label>
                                        <div class="relative">
                                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-black text-lg">alternate_email</span>
                                            <input name="username" type="text" required
                                                class="w-full pl-12 pr-4 py-2.5 bg-slate-50 border-none rounded-lg text-black focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-300 text-sm" 
                                                placeholder="budi" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[10px] font-bold text-black uppercase tracking-widest mb-1.5 ml-1">Nomor Rumah</label>
                                            <div class="relative">
                                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-black text-lg">home</span>
                                                <input name="nomor_rumah" type="text" required
                                                    class="w-full pl-11 pr-3 py-2.5 bg-slate-50 border-none rounded-lg text-black focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-300 text-sm" 
                                                    placeholder="ET-5" />
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-black uppercase tracking-widest mb-1.5 ml-1">Nomor Telepon</label>
                                            <div class="relative">
                                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-black text-lg">call</span>
                                                <input name="telp" type="tel" required
                                                    class="w-full pl-11 pr-3 py-2.5 bg-slate-50 border-none rounded-lg text-black focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-300 text-sm" 
                                                    placeholder="0812..." />
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-black uppercase tracking-widest mb-1.5 ml-1">Role</label>
                                        <div class="relative">
                                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-black text-lg pointer-events-none">badge</span>
                                            <select name="role" required
                                                class="w-full pl-12 pr-10 py-2.5 bg-slate-50 border-none rounded-lg text-black appearance-none focus:ring-2 focus:ring-emerald-500/20 transition-all text-sm">
                                                <option value="warga">Warga</option>
                                                <option value="ketua_rt">Ketua RT</option>
                                                <option value="bendahara">Bendahara</option>
                                                <option value="admin">Admin</option>
                                            </select>
                                            
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-bold text-black uppercase tracking-widest mb-1.5 ml-1">Password</label>
                                        <div class="relative">
                                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-black text-lg">lock</span>
                                            <input name="password" type="password" required
                                                class="w-full pl-12 pr-4 py-2.5 bg-slate-50 border-none rounded-lg text-black focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-300 text-sm" 
                                                placeholder="•••" />
                                        </div>
                                    </div>
                                </div>

                                <div class="px-8 py-5 border-t border-slate-50 bg-slate-50/30 flex justify-end items-center gap-3">
                                    <button type="button" @click="open = false"
                                        class="px-4 py-2 text-black font-bold text-xs uppercase tracking-widest hover:text-emerald-700 transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-6 py-2.5 bg-[#10b981] text-white rounded-full font-bold text-xs uppercase tracking-widest shadow-lg shadow-emerald-200 hover:bg-emerald-600 transition-all">
                                        Simpan User
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">Nama</th>
                            <th class="px-6 py-3">Username</th>
                            <th class="px-6 py-3">Rumah</th>
                            <th class="px-6 py-3">Telepon</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">

                        @foreach($users as $u)
                        @php
                            $words = explode(' ', $u->name);
                            $initials = strtoupper(
                                substr($words[0], 0, 1) .
                                (isset($words[1]) ? substr($words[1], 0, 1) : '')
                            );
                        @endphp

                        <tr x-data="{ openEdit: false }" class="hover:bg-gray-50 transition">

                            <!-- Nama + Avatar -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 flex items-center justify-center rounded-full bg-emerald-100 text-emerald-600 font-bold text-xs">
                                        {{ $initials }}
                                    </div>
                                    <span class="font-semibold text-gray-800">
                                        {{ $u->name }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $u->username }}
                            </td>

                            <td class="px-6 py-4 text-gray-600 font-medium">
                                {{ $u->nomor_rumah }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $u->telp }}
                            </td>

                            <!-- Role -->
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($u->role == 'admin') bg-red-100 text-red-600
                                    @elseif($u->role == 'bendahara') bg-blue-100 text-blue-600
                                    @elseif($u->role == 'ketua') bg-purple-100 text-purple-600
                                    @else bg-gray-100 text-gray-600
                                    @endif">
                                    {{ ucfirst($u->role) }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-1">

                                    <!-- Edit -->
                                    <button @click="openEdit = true"
                                    class="p-2 text-gray-500 hover:text-emerald-600">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>

                                    <!-- Reset -->
                                    <a href="/users/{{ $u->id }}/reset-password"
                                       class="p-2 text-gray-500 hover:text-amber-600">
                                        <span class="material-symbols-outlined text-[18px]">key</span>
                                    </a>

                                    <!-- Delete -->
                                    <form action="/users/{{ $u->id }}" method="POST"
                                          onsubmit="return confirm('Yakin hapus user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="p-2 text-gray-500 hover:text-red-600">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>

                                </div>
                                <!-- MODAL EDIT USER -->
                                <div x-cloak x-show="openEdit" x-transition 
                                    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                                    
                                    <div @click.away="openEdit = false" 
                                        class="bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden flex flex-col">
                                        
                                        <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-white">
                                            <h3 class="text-xl font-bold tracking-tight text-black">Edit User</h3>
                                            <button @click="openEdit = false" class="text-slate-400 hover:text-black transition-colors">
                                                <span class="material-symbols-outlined">close</span>
                                            </button>
                                        </div>

                                        <form method="POST" action="/users/{{ $u->id }}">
                                            @csrf
                                            @method('PUT')
                                            
                                            <div class="px-8 py-6 space-y-4">
                                                <div>
                                                    <label class="block text-[10px] font-bold text-black uppercase tracking-widest mb-1.5 ml-1">Nama Lengkap</label>
                                                    <div class="relative">
                                                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-black text-lg">person</span>
                                                        <input name="name" type="text" value="{{ $u->name }}" required
                                                            class="w-full pl-12 pr-4 py-2.5 bg-slate-50 border-none rounded-lg text-black focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-300 text-sm" 
                                                            placeholder="Nama Lengkap" />
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="block text-[10px] font-bold text-black uppercase tracking-widest mb-1.5 ml-1">Username</label>
                                                    <div class="relative">
                                                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-black text-lg">alternate_email</span>
                                                        <input name="username" type="text" value="{{ $u->username }}" required
                                                            class="w-full pl-12 pr-4 py-2.5 bg-slate-50 border-none rounded-lg text-black focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-300 text-sm" 
                                                            placeholder="Username" />
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-black uppercase tracking-widest mb-1.5 ml-1">Nomor Rumah</label>
                                                        <div class="relative">
                                                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-black text-lg">home</span>
                                                            <input name="nomor_rumah" type="text" value="{{ $u->nomor_rumah }}" 
                                                                class="w-full pl-11 pr-3 py-2.5 bg-slate-50 border-none rounded-lg text-black focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-300 text-sm" 
                                                                placeholder="A-12" />
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-black uppercase tracking-widest mb-1.5 ml-1">Nomor Telepon</label>
                                                        <div class="relative">
                                                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-black text-lg">call</span>
                                                            <input name="telp" type="tel" value="{{ $u->telp }}" required
                                                                class="w-full pl-11 pr-3 py-2.5 bg-slate-50 border-none rounded-lg text-black focus:ring-2 focus:ring-emerald-500/20 transition-all placeholder:text-slate-300 text-sm" 
                                                                placeholder="0812..." />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div>
                                                    <label class="block text-[10px] font-bold text-black uppercase tracking-widest mb-1.5 ml-1">Role</label>
                                                    <div class="relative">
                                                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-black text-lg pointer-events-none">badge</span>
                                                        <select name="role" required
                                                            class="w-full pl-12 pr-10 py-2.5 bg-slate-50 border-none rounded-lg text-black appearance-none focus:ring-2 focus:ring-emerald-500/20 transition-all text-sm">
                                                            <option value="warga" {{ $u->role=='warga'?'selected':'' }}>Warga</option>
                                                            <option value="ketua_rt" {{ $u->role=='ketua_rt'?'selected':'' }}>Ketua RT</option>
                                                            <option value="bendahara" {{ $u->role=='bendahara'?'selected':'' }}>Bendahara</option>
                                                            <option value="admin" {{ $u->role=='admin'?'selected':'' }}>Admin</option>
                                                        </select>                                                        
                                                    </div>
                                                </div>                                            
                                            </div>

                                            <div class="px-8 py-5 border-t border-slate-50 bg-slate-50/30 flex justify-end items-center gap-3">
                                                <button type="button" @click="openEdit = false"
                                                    class="px-4 py-2 text-black font-bold text-xs uppercase tracking-widest hover:text-emerald-700 transition-colors">
                                                    Batal
                                                </button>
                                                <button type="submit"
                                                    class="px-6 py-2.5 bg-[#10b981] text-white rounded-full font-bold text-xs uppercase tracking-widest shadow-lg shadow-emerald-200 hover:bg-emerald-600 transition-all">
                                                    Update User
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>

                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>

    </div>
</x-app-layout>