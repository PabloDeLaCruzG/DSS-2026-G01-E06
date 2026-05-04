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
 * - Index: listado / filtros / búsqueda (+ export CSV)
 * - Create/Store: creación de usuarios
 * - Show: ver perfil
 * - Edit/Update: editar usuario
 * - Destroy: eliminar usuario
 * - Ban/Unban: acciones de baneo (opcionales)
 */
class UserController extends Controller
{
    /**
     * Lista de roles permitidos por la aplicación (coincide con el ENUM de la BD).
     *
     * @var array<string,string>
     */
    protected array $allowedRoles = [
        'admin' => 'Administrador',
        'user'  => 'Usuario',
    ];

    /**
     * Mostrar listado de usuarios con filtros, búsqueda y opción de exportar CSV.
     *
     * Si la query string contiene `export=csv` devuelve un CSV descargable con
     * los mismos filtros aplicados.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::with('professionalProfile');

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

        // Si piden exportar CSV, generamos descarga en streaming
        if ($request->query('export') === 'csv') {
            $filename = 'users_' . now()->format('Ymd_His') . '.csv';

            $callback = function () use ($query) {
                $out = fopen('php://output', 'w');

                // BOM para Excel y UTF-8
                fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

                // Cabecera CSV
                fputcsv($out, [
                    'ID',
                    'Nombre',
                    'Email',
                    'Rol',
                    'Departamento',
                    'Baneado',
                    'Creado en',
                    'Actualizado en',
                ]);

                // Recorremos en chunks para no sobrecargar la memoria
                $query->orderBy('id')->chunk(500, function ($users) use ($out) {
                    foreach ($users as $u) {
                        fputcsv($out, [
                            $u->id,
                            $u->name,
                            $u->email,
                            $u->role,
                            $u->department,
                            $u->is_banned ? 'sí' : 'no',
                            optional($u->created_at)->toDateTimeString(),
                            optional($u->updated_at)->toDateTimeString(),
                        ]);
                    }
                });

                fclose($out);
            };

            return response()->streamDownload($callback, $filename, [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]);
        }

        // Si no es export, renderizamos la vista normal (paginada)
        $users = $query->latest()->paginate(10);
        $totalUsers = User::count();
        $pendingReports = Report::where('status', 'OPEN')->count();

        // Datos para partials / modales
        $roles = $this->allowedRoles;
        $departments = ['Gerencia', 'Soporte', 'Ventas'];

        return view('admin.users.index', compact('users', 'totalUsers', 'pendingReports', 'roles', 'departments'));
    }

    /**
     * Mostrar formulario para crear usuario.
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
     * Almacenar un nuevo usuario (usa CreateUserRequest para validación).
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
     * Banea a un usuario (marca is_banned = true).
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ban(User $user)
    {
        abort_if($user->isAdmin(), 403, 'No puedes banear a un administrador.');
        $user->update(['is_banned' => true]);

        return back()->with('success', "Usuario {$user->name} baneado.");
    }

    /**
     * Quita el baneo a un usuario.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);
        return back()->with('success', "Usuario {$user->name} reactivado.");
    }

    /**
     * Mostrar perfil de un usuario.
     *
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $user->loadCount(['gameAds', 'orders', 'reviews', 'reports']);
        $user->load('professionalProfile');

        return view('admin.users.show', compact('user'));
    }

    /**
     * Mostrar formulario de edición para un usuario.
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
     * Actualizar usuario.
     *
     * - Si password viene vacío no se modifica.
     * - Validación inline; puedes moverla a UpdateUserRequest si prefieres.
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

        // Si envían password, la hasheamos, si no, la eliminamos del array
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
     * Eliminar usuario.
     *
     * - Protege la eliminación de administradores.
     * - Si prefieres soft deletes, activa SoftDeletes en el modelo User.
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