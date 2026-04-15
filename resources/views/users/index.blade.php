<x-app-layout>
    <x-slot name="header">
        <h2>Data User</h2>
    </x-slot>

    <div class="p-6">

        <a href="/users/create">+ Tambah User</a>

        <table border="1" cellpadding="10">
            <tr>
                <th>Nama</th>
                <th>Username</th>
                <th>Rumah</th>
                <th>telepon</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>

            @foreach($users as $u)
            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ $u->username }}</td>
                <td>{{ $u->nomor_rumah }}</td>
                <td>{{ $u->telp }}</td>
                <td>{{ $u->role }}</td>
                <td>
                    <a href="/users/{{ $u->id }}/edit">Edit</a> |

                    <a href="/users/{{ $u->id }}/reset-password">Reset</a> |

                    <form action="/users/{{ $u->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach

        </table>

    </div>
</x-app-layout>