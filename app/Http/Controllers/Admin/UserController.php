<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filtros por tab
        if ($request->filter === 'admins') {
            $query->where('role', 'admin');
        } elseif ($request->filter === 'moderators') {
            $query->where('role', 'moderator');
        } elseif ($request->filter === 'active') {
            $query->where('is_banned', false);
        } elseif ($request->filter === 'banned') {
            $query->where('is_banned', true);
        }

        // Búsqueda
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $users = $query->latest('last_login_at')->paginate(10);
        $totalUsers = User::count();
        $pendingReports = Report::where('status', 'OPEN')->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'pendingReports'));
    }

    public function ban(User $user)
    {
        abort_if($user->isAdmin(), 403);
        $user->update(['is_banned' => true]);
        return back()->with('success', "Usuario {$user->name} baneado.");
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);
        return back()->with('success', "Usuario {$user->name} reactivado.");
    }
}