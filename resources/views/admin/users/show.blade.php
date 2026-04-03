@extends('admin.layouts.layout')
@section('title', 'Perfil de usuario')

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-text-muted mb-4 inline-block">← Volver</a>

    <div class="bg-surface rounded-xl border border-border p-6">
        <h2 class="text-xl font-bold text-text-main mb-4">{{ $user->name }}</h2>

        <dl class="grid grid-cols-1 gap-3 text-sm text-text-muted">
            <div>
                <dt class="font-medium text-text-muted">Email</dt>
                <dd class="text-text-main">{{ $user->email }}</dd>
            </div>
            <div>
                <dt class="font-medium text-text-muted">Rol</dt>
                <dd class="text-text-main">{{ $user->role }}</dd>
            </div>
            <div>
                <dt class="font-medium text-text-muted">Departamento</dt>
                <dd class="text-text-main">{{ $user->department ?? '—' }}</dd>
            </div>
            <div>
                <dt class="font-medium text-text-muted">Registrado</dt>
                <dd class="text-text-main">{{ $user->created_at->format('Y-m-d H:i') }}</dd>
            </div>
            <div>
                <dt class="font-medium text-text-muted">Última actividad</dt>
                <dd class="text-text-main">{{ $user->updated_at ? $user->updated_at->diffForHumans() : '—' }}</dd>
            </div>
        </dl>

        <div class="mt-6 flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-primary text-white rounded">Editar</a>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-surface border border-border rounded">Volver</a>
        </div>
    </div>
</div>
@endsection