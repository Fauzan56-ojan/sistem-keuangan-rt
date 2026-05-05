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
        $users = $query->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'telp' => $request->telp,
            'nomor_rumah' => $request->nomor_rumah,
            'role' => $request->role,
            'status_aktif' => 1,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/users');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'telp' => $request->telp,
            'nomor_rumah' => $request->nomor_rumah,
            'role' => $request->role,
        ]);

        return redirect('/users');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->status_aktif = 0;
        $user->save();

        return redirect('/users');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        $user->password = \Hash::make('123');
        $user->save();

        return redirect('/users')->with('success', 'Password direset ke 123456');
    }
}