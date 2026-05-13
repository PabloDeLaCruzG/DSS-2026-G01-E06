<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Review;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('user.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Perfil actualizado');
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = Auth::user();

        if ($user->avatar_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar_path);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        $user->update([
            'avatar_path' => $path,
        ]);

        return back()->with('success', 'Avatar actualizado correctamente.');
    }

    public function destroy()
    {
        $user = Auth::user();

        Auth::logout();

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->delete();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Tu cuenta ha sido eliminada.');
    }

    public function show(\App\Models\User $user)
    {
        $avg = Review::whereHas('gameAd', fn($q) => $q->where('user_id', $user->id))
                    ->avg('rating') ?? 0;
        $user->update(['reputation' => round($avg, 2)]);

        $ads = $user->gameAds()->with('game')->latest()->paginate(12);
        $reviews = Review::whereHas('gameAd', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->with('user', 'gameAd.game')
            ->latest()
            ->take(4)
            ->get();

        return view('user.profile.show', compact('user', 'ads', 'reviews'));
    }
}