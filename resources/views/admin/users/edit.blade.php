@extends('admin.layouts.layout')
@section('title', 'Editar usuario')

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-text-muted mb-4 inline-block">← Volver</a>

    <div class="bg-surface rounded-xl border border-border p-6">
        <h3 class="text-lg font-semibold text-text-main mb-4">Editar usuario</h3>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            @php
                // Prepara valores para el partial: usa old() si hay, si no los valores del usuario
                $old = function($k, $default=null) use($user) {
                    return old($k, $user->{$k} ?? $default);
                };
            @endphp

            <div class="space-y-4">
                {{-- Nombre --}}
                <div>
                    <label class="block text-xs text-text-muted mb-1">Nombre completo</label>
                    <input name="name" value="{{ $old('name') }}" required
                           class="w-full bg-background border border-border rounded px-3 py-2 text-text-main"
                           @error('name') aria-invalid="true" @enderror />
                    @error('name') <p class="text-xs text-red-400 mt-1 input-error">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs text-text-muted mb-1">Correo electrónico</label>
                    <input name="email" type="email" value="{{ $old('email') }}" required
                           class="w-full bg-background border border-border rounded px-3 py-2 text-text-main"
                           @error('email') aria-invalid="true" @enderror />
                    @error('email') <p class="text-xs text-red-400 mt-1 input-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Rol --}}
                    <div>
                        <label class="block text-xs text-text-muted mb-1">Rol</label>
                        <select name="role" required class="w-full bg-background border border-border rounded px-3 py-2 text-text-main"
                                @error('role') aria-invalid="true" @enderror>
                            @php $rolesLocal = $roles ?? ['user' => 'Usuario', 'admin' => 'Administrador']; @endphp
                            @foreach($rolesLocal as $key => $label)
                                <option value="{{ $key }}" {{ $old('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role') <p class="text-xs text-red-400 mt-1 input-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Departamento --}}
                    <div>
                        <label class="block text-xs text-text-muted mb-1">Departamento</label>
                        <input name="department" value="{{ $old('department') }}" class="w-full bg-background border border-border rounded px-3 py-2 text-text-main"
                               @error('department') aria-invalid="true" @enderror />
                        @error('department') <p class="text-xs text-red-400 mt-1 input-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Contraseña (opcional) --}}
                <div>
                    <label class="block text-xs text-text-muted mb-1">Contraseña (dejar en blanco para no cambiar)</label>
                    <input name="password" type="password" class="w-full bg-background border border-border rounded px-3 py-2 text-text-main"
                           @error('password') aria-invalid="true" @enderror />
                    @error('password') <p class="text-xs text-red-400 mt-1 input-error">{{ $message }}</p> @enderror
                </div>

                {{-- Confirmar contraseña --}}
                <div>
                    <label class="block text-xs text-text-muted mb-1">Confirmar contraseña</label>
                    <input name="password_confirmation" type="password" class="w-full bg-background border border-border rounded px-3 py-2 text-text-main" />
                </div>

                <div class="flex items-center justify-end gap-3 mt-6">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded bg-surface text-text-muted border border-border hover:text-text-main transition">Cancelar</a>
                    <button type="submit" class="px-4 py-2 rounded bg-primary text-white hover:bg-primary-hover transition">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection