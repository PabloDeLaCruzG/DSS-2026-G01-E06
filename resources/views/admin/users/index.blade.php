@extends('admin.layout')
@section('title', 'Gestión de Usuarios')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="text-xs text-gray-500">
        <span class="text-teal-400">ADMIN</span>
        <span class="mx-1">›</span>
        <span class="text-teal-400 uppercase tracking-wide">Gestión de Usuarios</span>
    </div>

    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Gestión de Usuarios</h1>
            <p class="text-sm text-gray-400 mt-1">Supervisa la actividad, roles y estados de los ciudadanos de GameLink.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['export' => 'csv'])) }}"
               class="flex items-center gap-2 px-4 py-2 bg-[#1e2130] border border-white/10 rounded-lg text-sm text-gray-300 hover:bg-white/5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Exportar CSV
            </a>
            <button class="flex items-center gap-2 px-4 py-2 bg-teal-500 hover:bg-teal-400 rounded-lg text-sm font-medium text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nuevo Usuario
            </button>
        </div>
    </div>

    {{-- Search + Filters --}}
    <div class="space-y-3">
        <div class="relative max-w-sm">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 105 11a6 6 0 0012 0z"/></svg>
            <form method="GET">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar juegos, consolas..."
                    class="w-full bg-[#1e2130] border border-white/10 rounded-lg pl-9 pr-4 py-2 text-sm text-gray-300 placeholder-gray-600 focus:outline-none focus:border-teal-500/50">
            </form>
        </div>

        {{-- Tabs --}}
        <div class="flex gap-2 flex-wrap">
            @php
                $filters = [
                    '' => "Todos ({$totalUsers})",
                    'admins' => 'Administradores',
                    'moderators' => 'Moderadores',
                    'active' => 'Activos',
                    'banned' => 'Baneados',
                ];
            @endphp
            @foreach($filters as $value => $label)
                <a href="{{ route('admin.users.index', array_merge(request()->except('filter', 'page'), $value ? ['filter' => $value] : [])) }}"
                   class="px-3 py-1.5 rounded-full text-xs font-medium transition
                   {{ request('filter', '') === $value
                       ? 'bg-teal-500 text-white'
                       : 'bg-[#1e2130] text-gray-400 hover:bg-white/10 border border-white/10' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-[#13151c] rounded-xl border border-white/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5 text-xs text-gray-500 uppercase tracking-wide">
                    <th class="text-left px-5 py-3">ID</th>
                    <th class="text-left px-5 py-3">Nombre / Usuario</th>
                    <th class="text-left px-5 py-3">Rol</th>
                    <th class="text-left px-5 py-3">Estado</th>
                    <th class="text-left px-5 py-3">Última Conexión</th>
                    <th class="text-right px-5 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($users as $user)
                <tr class="hover:bg-white/[0.02] transition">
                    <td class="px-5 py-4 text-gray-500 font-mono text-xs">#GL-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-teal-500 to-blue-600 flex items-center justify-center text-xs font-bold shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-white text-sm">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        @if($user->role === 'admin')
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-teal-500/10 text-teal-400 border border-teal-500/20">admin</span>
                        @elseif($user->role === 'moderator')
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">moderador</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-white/5 text-gray-400 border border-white/10">usuario</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        @if($user->is_banned)
                            <span class="flex items-center gap-1.5 text-xs text-red-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                BANEADO
                            </span>
                        @else
                            <span class="flex items-center gap-1.5 text-xs text-green-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                ACTIVO
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-xs text-gray-500">
                        {{ $user->updated_at ? $user->updated_at->diffForHumans() : '—' }}
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if($user->is_banned)
                                <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 text-xs rounded bg-green-500/10 text-green-400 border border-green-500/20 hover:bg-green-500/20 transition">
                                        REACTIVAR
                                    </button>
                                </form>
                            @else
                                <a href="#" class="px-3 py-1 text-xs rounded bg-white/5 text-gray-400 border border-white/10 hover:bg-white/10 transition">
                                    VER PERFIL
                                </a>
                                @if(!$user->isAdmin())
                                <form method="POST" action="{{ route('admin.users.ban', $user) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 text-xs rounded bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20 transition">
                                        BANEAR
                                    </button>
                                </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-gray-500 text-sm">No se encontraron usuarios.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="px-5 py-3 border-t border-white/5 flex items-center justify-between">
            <p class="text-xs text-gray-500">Mostrando {{ $users->firstItem() }}–{{ $users->lastItem() }} de {{ $users->total() }} usuarios</p>
            <div class="flex gap-1">
                {{ $users->onEachSide(1)->links('admin.pagination') }}
            </div>
        </div>
    </div>

    {{-- Stats cards --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-[#13151c] rounded-xl border border-white/5 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-3">Crecimiento Mensual</p>
            <div class="flex items-end justify-between">
                <p class="text-2xl font-bold text-white">+14.2%</p>
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <p class="text-xs text-gray-600 mt-1">196 nuevos registros este mes</p>
        </div>
        <div class="bg-[#13151c] rounded-xl border border-white/5 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-3">Tasa de Retención</p>
            <div class="flex items-end justify-between">
                <p class="text-2xl font-bold text-white">68.5%</p>
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <p class="text-xs text-gray-600 mt-1">Calculado sobre últimos 90 días</p>
        </div>
        <div class="bg-[#13151c] rounded-xl border border-white/5 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-3">Reportes Pendientes</p>
            <div class="flex items-end justify-between">
                <p class="text-2xl font-bold text-white">{{ $pendingReports }}</p>
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            </div>
            <p class="text-xs text-gray-600 mt-1">Requieren atención inmediata</p>
        </div>
    </div>

</div>
@endsection