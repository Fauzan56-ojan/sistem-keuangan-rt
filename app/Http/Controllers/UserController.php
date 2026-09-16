<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $query = User::where('status_aktif', 1);

        if (request('search')) {
            $search = trim(request('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('nomor_rumah', 'like', "%$search%");
            });
        }
        $users = $query->paginate(10)->withQueryString();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|regex:/[a-zA-Z]/|max:100',
            'username' => 'required|string|regex:/[a-zA-Z]/|max:50|unique:users,username',
            'nomor_rumah' => 'required_if:role,warga|nullable|string|max:20|unique:users,nomor_rumah,status_aktif,1',
            'telp' => 'required|string|min:8|max:20|regex:/^\+?[0-9]+$/',
            'role' => 'required|in:warga,ketua_rt,bendahara,admin',
            'password' => 'required',
        ]);
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'telp' => $request->telp,
            'nomor_rumah' => $request->nomor_rumah,
            'role' => $request->role,
            'status_aktif' => 1,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/users')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|regex:/[a-zA-Z]/|max:100',
            'username'    => 'required|string|regex:/[a-zA-Z]/|max:50|unique:users,username,' . $id,
            'nomor_rumah' => 'required_if:role,warga|nullable|string|max:20|unique:users,nomor_rumah,' . $id . ',status_aktif,1',
            'telp'        => 'required|string|min:8|max:20|regex:/^\+?[0-9]+$/',
            'role'        => 'required|in:warga,ketua_rt,bendahara,admin',
        ]);

        $user->update([
            'name'        => $request->name,
            'username'    => $request->username,
            'telp'        => $request->telp,
            'nomor_rumah' => $request->nomor_rumah,
            'role'        => $request->role,
        ]);

        return redirect('/users')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->status_aktif = 0;
        $user->save();

        return redirect('/users')->with('success', 'User berhasil dihapus');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        $user->password = \Hash::make('123');
        $user->password_changed = false;
        $user->save();

        return redirect('/users')->with('success', 'Password direset ke 123');
    }
}