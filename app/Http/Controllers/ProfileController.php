<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|regex:/[a-zA-Z]/|max:100',
            'telp' => 'required|string|min:8|max:20|regex:/^\+?[0-9]+$/',

        ]);
        $request->user()->update([
            'name' => $request->name,
            'telp' => $request->telp,
        ]);

        return Redirect::route('settings.profile')->with('success', 'Profile berhasil diperbarui');
    }

    /**
     * Delete the user's account.
     */
    // public function destroy(Request $request): RedirectResponse
    // {
    //     $request->validateWithBag('userDeletion', [
    //         'password' => ['required', 'current_password'],
    //     ]);

    //     $user = $request->user();

    //     Auth::logout();

    //     $user->delete();

    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return Redirect::to('/');
    // }
}
