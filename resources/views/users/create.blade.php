<x-app-layout>
    <x-slot name="header">
        <h2>Tambah User</h2>
    </x-slot>

    <div class="p-6">

        <form method="POST" action="/users">
            @csrf

            <div>
                Nama
                <input type="text" name="name">
            </div>

            <div>
                Username
                <input type="text" name="username">
            </div>

            <div>
                Telepon
                <input type="text" name="telp">
            <div>
                
                Rumah
                <input type="text" name="nomor_rumah">
            </div>

            <div>
                Role
                <select name="role">
                    <option value="warga">Warga</option>
                    <option value="admin">Admin</option>
                    <option value="bendahara">Bendahara</option>
                    <option value="ketua_rt">Ketua RT</option>
                </select>
            </div>

            <div>
                Password
                <input type="password" name="password">
            </div>

            <button type="submit">Simpan</button>

        </form>

    </div>
</x-app-layout>