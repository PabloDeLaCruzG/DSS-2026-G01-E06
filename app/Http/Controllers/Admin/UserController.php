<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\CreateUserRequest;

class UserController extends Controller
{
    /**
     * Lista de roles permitidos por la aplicación (coincide con el ENUM de la BD).
     */
    protected array $allowedRoles = [
        'admin' => 'Administrador',
        'user'  => 'Usuario',
    ];

    public function index(Request $request)
    {
        $query = User::query();

        // Filtros por tab
        if ($request->filter === 'admins') {
            $query->where('role', 'admin');
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

        $users = $query->latest()->paginate(10);
        $totalUsers = User::count();
        $pendingReports = Report::where('status', 'OPEN')->count();

        // Variables para el partial del modal
        $roles = $this->allowedRoles;
        $departments = ['Gerencia', 'Soporte', 'Ventas'];

        return view('admin.users.index', compact('users', 'totalUsers', 'pendingReports', 'roles', 'departments'));
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

    public function create()
    {
        $roles = $this->allowedRoles;
        $departments = ['Gerencia', 'Soporte', 'Ventas'];
        return view('admin.users.create', compact('roles', 'departments'));
    }

    public function store(CreateUserRequest $request)
    {
        $data = $request->validated();

        // Forzar role seguro (por si alguien intenta inyectar otro valor)
        if (! array_key_exists($data['role'] ?? '', $this->allowedRoles)) {
            $data['role'] = 'user';
        }

        // Hash de la contraseña
        $data['password'] = Hash::make($data['password']);

        // Crea el usuario
        $user = User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'role'       => $data['role'],
            'department' => $data['department'] ?? null,
            'password'   => $data['password'],
        ]);

        return redirect()->route('admin.users.index')->with('success', "Usuario {$user->name} creado correctamente.");
    }
}