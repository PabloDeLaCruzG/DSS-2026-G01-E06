@extends('admin.layouts.layout')
@section('title', 'Perfil de ' . $user->name)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-sm text-text-muted hover:text-text-main transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Volver a usuarios
    </a>

    {{-- Header card --}}
    <div class="bg-surface rounded-xl border border-border p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">

            {{-- Avatar --}}
            <img src="{{ $user->avatar_url }}"
                 alt="{{ $user->name }}"
                 class="w-20 h-20 rounded-full object-cover border-2 border-border shrink-0">

            {{-- Info principal --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h1 class="text-2xl font-bold text-text-main truncate">{{ $user->name }}</h1>

                    {{-- Rol --}}
                    @if($user->role === 'admin')
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-primary/10 text-primary border border-primary/20">Admin</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-border text-text-muted border border-border">Usuario</span>
                    @endif

                    {{-- Estado --}}
                    @if($user->is_banned)
                        <span class="flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Baneado
                        </span>
                    @else
                        <span class="flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-accent/10 text-accent border border-accent/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>Activo
                        </span>
                    @endif

                    {{-- Profesional --}}
                    @if($user->isProfessional())
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">✔ Pro Verificado</span>
                    @elseif($user->professionalProfile)
                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">⏳ Pro Pendiente</span>
                    @endif
                </div>

                <p class="text-sm text-text-muted">{{ $user->email }}</p>
                <p class="text-xs text-text-muted mt-0.5 font-mono">#GL-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>

            {{-- Acciones --}}
            <div class="flex flex-wrap gap-2 shrink-0">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="px-4 py-2 text-sm rounded-lg bg-primary hover:bg-primary-hover text-white transition">
                    Editar
                </a>

                @if(!$user->isAdmin())
                    @if($user->is_banned)
                        <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 text-sm rounded-lg bg-accent/10 hover:bg-accent/20 text-accent border border-accent/20 transition">
                                Desbanear
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.users.ban', $user) }}"
                              onsubmit="return confirm('¿Banear a {{ addslashes($user->name) }}?')">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 text-sm rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 transition">
                                Banear
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          onsubmit="return confirm('¿Eliminar a {{ addslashes($user->name) }}? Esta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 text-sm rounded-lg bg-surface border border-border text-text-muted hover:text-red-400 hover:border-red-500/30 transition">
                            Eliminar
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-surface rounded-xl border border-border p-4 text-center">
            <p class="text-2xl font-bold text-text-main">{{ $user->game_ads_count }}</p>
            <p class="text-xs text-text-muted mt-1 uppercase tracking-wide">Anuncios</p>
        </div>
        <div class="bg-surface rounded-xl border border-border p-4 text-center">
            <p class="text-2xl font-bold text-text-main">{{ $user->orders_count }}</p>
            <p class="text-xs text-text-muted mt-1 uppercase tracking-wide">Pedidos</p>
        </div>
        <div class="bg-surface rounded-xl border border-border p-4 text-center">
            <p class="text-2xl font-bold text-text-main">{{ $user->reviews_count }}</p>
            <p class="text-xs text-text-muted mt-1 uppercase tracking-wide">Reseñas</p>
        </div>
        <div class="bg-surface rounded-xl border border-border p-4 text-center">
            <p class="text-2xl font-bold text-text-main">{{ number_format($user->reputation ?? 0, 1) }}</p>
            <p class="text-xs text-text-muted mt-1 uppercase tracking-wide">Reputación</p>
        </div>
    </div>

    {{-- Detalles --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        {{-- Cuenta --}}
        <div class="bg-surface rounded-xl border border-border p-5 space-y-4">
            <h2 class="text-sm font-semibold text-text-main uppercase tracking-wide">Cuenta</h2>

            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-text-muted">Saldo wallet</dt>
                    <dd class="text-text-main font-medium">{{ number_format($user->wallet_balance ?? 0, 2) }} €</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-text-muted">Rol</dt>
                    <dd class="text-text-main">{{ ucfirst($user->role) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-text-muted">Departamento</dt>
                    <dd class="text-text-main">{{ $user->department ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-text-muted">Reportes enviados</dt>
                    <dd class="text-text-main">{{ $user->reports_count }}</dd>
                </div>
            </dl>
        </div>

        {{-- Fechas --}}
        <div class="bg-surface rounded-xl border border-border p-5 space-y-4">
            <h2 class="text-sm font-semibold text-text-main uppercase tracking-wide">Actividad</h2>

            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-text-muted">Registrado</dt>
                    <dd class="text-text-main">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-text-muted">Última actividad</dt>
                    <dd class="text-text-main" title="{{ $user->updated_at->format('d/m/Y H:i') }}">
                        {{ $user->updated_at->diffForHumans() }}
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-text-muted">Email verificado</dt>
                    <dd class="text-text-main">
                        @if($user->email_verified_at)
                            <span class="text-accent">{{ $user->email_verified_at->format('d/m/Y') }}</span>
                        @else
                            <span class="text-text-muted">Sin verificar</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Perfil Profesional --}}
        @if($user->professionalProfile)
        <div class="sm:col-span-2 bg-surface rounded-xl border border-border p-5 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-text-main uppercase tracking-wide">Perfil Profesional</h2>
                @if($user->professionalProfile->is_verified)
                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">Verificado</span>
                @else
                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">Pendiente de verificación</span>
                @endif
            </div>

            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                <div>
                    <dt class="text-text-muted mb-0.5">Empresa</dt>
                    <dd class="text-text-main font-medium">{{ $user->professionalProfile->company_name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-text-muted mb-0.5">CIF</dt>
                    <dd class="text-text-main font-mono">{{ $user->professionalProfile->cif ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-text-muted mb-0.5">Web</dt>
                    <dd class="text-text-main">
                        @if($user->professionalProfile->website)
                            <a href="{{ $user->professionalProfile->website }}" target="_blank" rel="noopener"
                               class="text-primary hover:underline truncate">
                                {{ $user->professionalProfile->website }}
                            </a>
                        @else
                            —
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
        @endif

    </div>

</div>
@endsection
