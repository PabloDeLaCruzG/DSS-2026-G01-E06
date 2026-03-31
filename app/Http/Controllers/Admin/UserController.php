<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\CreateUserRequest;

/**
 * Controlador para la gestión de usuarios en el panel de administrador.
 *
 * - Index: listado / filtros / búsqueda
 * - Create/Store: creación de usuarios
 * - Show: ver perfil
 * - Edit/Update: editar usuario
 * - Destroy: eliminar usuario
 * - Ban/Unban: acciones de baneo (si aún las usas)
 */
class UserController extends Controller
{
    /**
     * Lista de roles permitidos por la aplicación (el ENUM de la BD).
     *
     * Llenar con los valores exactos soportados por la base de datos para evitar errores.
     *
     * @var array<string,string>
     */
    protected array $allowedRoles = [
        'admin' => 'Administrador',
        'user'  => 'Usuario',
    ];

    /**
     * Mostrar listado de usuarios con filtros y búsqueda.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtros por pestañas (tabs)
        if ($request->filter === 'admins') {
            $query->where('role', 'admin');
        } elseif ($request->filter === 'active') {
            $query->where('is_banned', false);
        } elseif ($request->filter === 'banned') {
            $query->where('is_banned', true);
        }

        // Búsqueda por nombre o email
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Orden y paginación
        $users = $query->latest()->paginate(10);

        // Estadísticas para la vista
        $totalUsers = User::count();
        $pendingReports = Report::where('status', 'OPEN')->count();

        // Datos para los partials (modal / forms)
        $roles = $this->allowedRoles;
        $departments = ['Gerencia', 'Soporte', 'Ventas'];

        return view('admin.users.index', compact('users', 'totalUsers', 'pendingReports', 'roles', 'departments'));
    }

    /**
     * Formulario para crear usuario.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = $this->allowedRoles;
        $departments = ['Gerencia', 'Soporte', 'Ventas'];

        return view('admin.users.create', compact('roles', 'departments'));
    }

    /**
     * Almacenar un nuevo usuario validado por CreateUserRequest.
     *
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateUserRequest $request)
    {
        $data = $request->validated();

        // Forzar role seguro (por si alguien intenta inyectar otro valor)
        if (! array_key_exists($data['role'] ?? '', $this->allowedRoles)) {
            $data['role'] = 'user';
        }

        // Hash de la contraseña antes de guardar
        $data['password'] = Hash::make($data['password']);

        // Crear usuario
        $user = User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'role'       => $data['role'],
            'department' => $data['department'] ?? null,
            'password'   => $data['password'],
        ]);

        return redirect()->route('admin.users.index')->with('success', "Usuario {$user->name} creado correctamente.");
    }

    /**
     * Mostrar el perfil de un usuario.
     *
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Mostrar el formulario de edición para un usuario.
     *
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roles = $this->allowedRoles;
        $departments = ['Gerencia', 'Soporte', 'Ventas'];

        return view('admin.users.edit', compact('user', 'roles', 'departments'));
    }

    /**
     * Actualizar un usuario.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email,' . $user->id,
            'role'       => 'required|in:admin,user',
            'department' => 'nullable|string|max:255',
            'password'   => 'nullable|string|min:8|confirmed',
        ];

        $data = $request->validate($rules);

        // Si envían password, la hasheamos; si no, la descartamos del array para evitar vaciarla.
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Forzar role a allowed roles por seguridad
        if (! array_key_exists($data['role'] ?? '', $this->allowedRoles)) {
            $data['role'] = 'user';
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', "Usuario {$user->name} actualizado.");
    }

    /**
     * Eliminar un usuario.
     * - Protege la eliminación de administradores.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        abort_if($user->isAdmin(), 403, 'No puedes eliminar a un administrador.');

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "Usuario {$user->name} eliminado.");
    }
}