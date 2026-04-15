<x-app-layout>
    <x-slot name="header">
        <h2>Edit User</h2>
    </x-slot>

    <div class="p-6">

        <form method="POST" action="/users/{{ $user->id }}">
            @csrf
            @method('PUT')

            <div>
                Nama
                <input type="text" name="name" value="{{ $user->name }}">
            </div>

            <div>
                Username
                <input type="text" name="username" value="{{ $user->username }}">
            </div>

            <div>
                Telepon
                <input type="text" name="telp" value="{{ $user->telp }}">
            </div>

            <div>
                Rumah
                <input type="text" name="nomor_rumah" value="{{ $user->nomor_rumah }}">
            </div>

            <div>
                Role
                <select name="role">
                    <option value="warga" {{ $user->role=='warga'?'selected':'' }}>Warga</option>
                    <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
                    <option value="bendahara" {{ $user->role=='bendahara'?'selected':'' }}>Bendahara</option>
                    <option value="ketua_rt" {{ $user->role=='ketua_rt'?'selected':'' }}>Ketua RT</option>
                </select>
            </div>

            <button type="submit">Update</button>

        </form>

    </div>
</x-app-layout>