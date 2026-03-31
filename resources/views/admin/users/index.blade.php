@extends('admin.layouts.layout')
@section('title', 'Gestión de Usuarios')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-text-main">Gestión de Usuarios</h1>
            <p class="text-sm text-text-muted mt-1">Supervisa la actividad, roles y estados de los ciudadanos de GameLink.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['export' => 'csv'])) }}"
               class="flex items-center gap-2 px-4 py-2 bg-surface border border-border rounded-lg text-sm text-text-muted hover:text-text-main transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Exportar CSV
            </a>
            <button id="openCreateUser" type="button" class="flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary-hover rounded-lg text-sm font-medium text-white transition shadow-lg shadow-primary/20">
                <svg class="w-4 h-4" ...></svg>Nuevo Usuario
            </button>
        </div>
    </div>

    {{-- Search + Filters --}}
    <div class="space-y-3">
        <div class="relative max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/></svg>
            <form method="GET">
                <input type="hidden" name="filter" value="{{ request('filter') }}">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar por nombre o email..."
                    class="w-full bg-surface border border-border rounded-lg pl-9 pr-4 py-2 text-sm text-text-main placeholder-text-muted focus:outline-none focus:border-primary transition">
            </form>
        </div>

        {{-- Tabs --}}
        <div class="flex gap-2 flex-wrap">
            @php
                $filters = [
                    '' => "Todos ({$totalUsers})",
                    'admins' => 'Administradores',
                    'active' => 'Activos',
                    'banned' => 'Baneados',
                ];
            @endphp
            @foreach($filters as $value => $label)
                <a href="{{ route('admin.users.index', array_merge(request()->except('filter', 'page'), $value ? ['filter' => $value] : [])) }}"
                   class="px-3 py-1.5 rounded-full text-xs font-medium transition
                   {{ request('filter', '') === $value
                       ? 'bg-primary text-white shadow-sm shadow-primary/30'
                       : 'bg-surface text-text-muted hover:text-text-main border border-border' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-surface rounded-xl border border-border overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-border text-xs text-text-muted uppercase tracking-wide">
                    <th class="text-left px-5 py-3">ID</th>
                    <th class="text-left px-5 py-3">Nombre / Usuario</th>
                    <th class="text-left px-5 py-3">Rol</th>
                    <th class="text-left px-5 py-3">Estado</th>
                    <th class="text-left px-5 py-3">Última Actividad</th>
                    <th class="text-right px-5 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($users as $user)
                <tr class="hover:bg-background/50 transition">
                    <td class="px-5 py-4 text-text-muted font-mono text-xs">#GL-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-xs font-bold text-white shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-text-main text-sm">{{ $user->name }}</p>
                                <p class="text-xs text-text-muted">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        @if($user->role === 'admin')
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-primary/10 text-primary border border-primary/20">admin</span>
                        @elseif($user->role === 'moderator')
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-accent/10 text-accent border border-accent/20">moderador</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-border text-text-muted border border-border">usuario</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        @if($user->is_banned)
                            <span class="flex items-center gap-1.5 text-xs text-red-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                BANEADO
                            </span>
                        @else
                            <span class="flex items-center gap-1.5 text-xs text-accent">
                                <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
                                ACTIVO
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-xs text-text-muted">
                        {{ $user->updated_at ? $user->updated_at->diffForHumans() : '—' }}
                    </td>
                    <td class="px-5 py-4 text-right">
    <div class="flex items-center justify-end gap-2">
        {{-- Ver perfil --}}
        <a href="{{ route('admin.users.show', $user) }}" class="px-3 py-1 text-xs rounded bg-surface text-text-muted border border-border hover:text-text-main hover:border-primary/30 transition">
            VER PERFIL
        </a>

        {{-- Editar --}}
        <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1 text-xs rounded bg-surface text-text-muted border border-border hover:text-text-main transition">
            EDITAR
        </a>

        {{-- Eliminar --}}
        @if (! $user->isAdmin()) {{-- evita eliminar admins --}}
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1 text-xs rounded bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20 transition">
                    ELIMINAR
                </button>
            </form>
        @endif
    </div>
</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-text-muted text-sm">No se encontraron usuarios.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="px-5 py-3 border-t border-border flex items-center justify-between">
            <p class="text-xs text-text-muted">
                Mostrando {{ $users->firstItem() }}–{{ $users->lastItem() }} de {{ $users->total() }} usuarios
            </p>
            <div class="flex gap-1">
             {{ $users->onEachSide(1)->links('admin.layouts.pagination') }}
            </div>
        </div>
        @endif
    </div>

    {{-- Stats cards --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-surface rounded-xl border border-border p-5">
            <p class="text-xs text-text-muted uppercase tracking-wide mb-3">Crecimiento Mensual</p>
            <div class="flex items-end justify-between">
                <p class="text-2xl font-bold text-text-main">+14.2%</p>
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <p class="text-xs text-text-muted mt-1">196 nuevos registros este mes</p>
        </div>
        <div class="bg-surface rounded-xl border border-border p-5">
            <p class="text-xs text-text-muted uppercase tracking-wide mb-3">Tasa de Retención</p>
            <div class="flex items-end justify-between">
                <p class="text-2xl font-bold text-text-main">68.5%</p>
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <p class="text-xs text-text-muted mt-1">Calculado sobre últimos 90 días</p>
        </div>
        <div class="bg-surface rounded-xl border border-border p-5">
            <p class="text-xs text-text-muted uppercase tracking-wide mb-3">Reportes Pendientes</p>
            <div class="flex items-end justify-between">
                <p class="text-2xl font-bold text-text-main">{{ $pendingReports }}</p>
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            </div>
            <p class="text-xs text-text-muted mt-1">Requieren atención inmediata</p>
        </div>
    </div>

</div>
{{-- Modal Crear Usuario --}}
<div id="createUserModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-lg">
        <div class="bg-surface rounded-xl border border-border p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-text-main">Crear Nuevo Usuario</h3>
                <button id="closeCreateUser" class="text-text-muted text-xl leading-none">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                @include('admin.layouts._form')
                <div class="flex items-center justify-end gap-3 mt-6">
                    <button type="button" id="cancelCreateUser" class="px-4 py-2 rounded bg-surface text-text-muted border border-border hover:text-text-main transition">Cancelar</button>
                    <button type="submit" class="px-4 py-2 rounded bg-primary text-white hover:bg-primary-hover transition">+ Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('createUserModal');
    const openBtn = document.getElementById('openCreateUser');
    const closeBtn = document.getElementById('closeCreateUser');
    const cancelBtn = document.getElementById('cancelCreateUser');
    const form = modal ? modal.querySelector('form') : null;

    function openCreateUserModal() {
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        const first = modal.querySelector('input[name="name"], input, select, textarea');
        if (first) first.focus();
        console.debug('[Admin] Modal abierto');
    }

    function clearFormErrors() {
        if (!modal) return;
        modal.querySelectorAll('.input-error').forEach(n => n.remove());
        modal.querySelectorAll('[aria-invalid="true"]').forEach(el => el.removeAttribute('aria-invalid'));
        modal.querySelectorAll('[aria-describedby]').forEach(el => el.removeAttribute('aria-describedby'));
        console.debug('[Admin] Errores limpiados');
    }

    function clearFormFields() {
        if (!form) return;

        form.querySelectorAll('input, textarea, select').forEach(el => {
            const tag = el.tagName.toLowerCase();
            const type = (el.type || '').toLowerCase();


            if (type === 'hidden') {
                if (el.name === '_token' || el.name === '_method') {

                    return;
                }

                el.value = '';
                return;
            }

            if (type === 'checkbox' || type === 'radio') {
                el.checked = false;
            } else if (tag === 'select') {
                try { el.selectedIndex = 0; } catch(e) {}
                Array.from(el.options).forEach(opt => opt.removeAttribute('selected'));
            } else {
                el.value = '';
            }
            el.classList.remove('border-red-500', 'ring-red-500');
        });

        console.debug('[Admin] Campos del formulario limpiados (preservando _token)');
    }

    function closeCreateUserModal() {
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        clearFormErrors();
        clearFormFields();
        console.debug('[Admin] Modal cerrado y form reseteado (limpio)');
    }

    // Listeners
    if (openBtn) openBtn.addEventListener('click', function (e) { e.preventDefault(); openCreateUserModal(); });
    if (closeBtn) closeBtn.addEventListener('click', function(e){ e.preventDefault(); closeCreateUserModal(); });
    if (cancelBtn) cancelBtn.addEventListener('click', function (e) { e.preventDefault(); closeCreateUserModal(); });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (!modal) return;
            if (!modal.classList.contains('hidden')) closeCreateUserModal();
        }
    });

    // Reabrir modal si hay errores de validación devueltos por el servidor
    @if ($errors->any())
        console.debug('[Admin] Errores de validación detectados — reabriendo modal');
        openCreateUserModal();
    @endif

    // Si hay success, asegurarse de que el form quede limpio
    @if (session('success'))
        console.debug('[Admin] Éxito: {{ addslashes(session('success')) }}');
        if (form) {
            clearFormErrors();
            clearFormFields();
        }
    @endif

});
</script>
@endsection