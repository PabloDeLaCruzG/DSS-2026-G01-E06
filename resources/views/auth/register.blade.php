@extends('layouts.app')

@section('title', 'Crear cuenta – GameLink')

@section('content')
<div class="flex items-center justify-center min-h-[70vh] py-8">
    <div class="w-full max-w-md bg-surface border border-border rounded-xl p-8 shadow-lg">

        <!-- Logo + título -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center text-2xl mb-3">
                🎮
            </div>
            <h1 class="text-2xl font-bold text-text-main">Crear cuenta</h1>
            <p class="text-sm text-text-muted mt-1">Únete a la comunidad GameLink</p>
        </div>

        <!-- Formulario -->
        <form method="POST" action="/register" class="space-y-5">
            @csrf

            <!-- Nombre -->
            <div>
                <label for="name" class="block text-sm font-medium text-text-muted mb-1.5">
                    Nombre
                </label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="w-full bg-background border @error('name') border-red-500 @else border-border @enderror rounded-lg px-4 py-2.5 text-sm text-text-main placeholder-text-muted outline-none focus:border-primary transition-colors"
                    placeholder="Tu nombre"
                >
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-text-muted mb-1.5">
                    Correo electrónico
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full bg-background border @error('email') border-red-500 @else border-border @enderror rounded-lg px-4 py-2.5 text-sm text-text-main placeholder-text-muted outline-none focus:border-primary transition-colors"
                    placeholder="tu@email.com"
                >
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-text-muted mb-1.5">
                    Contraseña
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    class="w-full bg-background border @error('password') border-red-500 @else border-border @enderror rounded-lg px-4 py-2.5 text-sm text-text-main placeholder-text-muted outline-none focus:border-primary transition-colors"
                    placeholder="Mínimo 8 caracteres"
                >
                @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmar Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-text-muted mb-1.5">
                    Confirmar contraseña
                </label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full bg-background border border-border rounded-lg px-4 py-2.5 text-sm text-text-main placeholder-text-muted outline-none focus:border-primary transition-colors"
                    placeholder="Repite la contraseña"
                >
            </div>

            <!-- Botón -->
            <button
                type="submit"
                class="w-full bg-primary hover:bg-primary-hover text-white font-semibold rounded-lg py-2.5 text-sm transition-colors mt-2"
            >
                Crear cuenta
            </button>
        </form>

        <!-- Enlace a login -->
        <p class="text-center text-sm text-text-muted mt-6">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-primary hover:text-accent transition-colors font-medium">
                Inicia sesión
            </a>
        </p>

    </div>
</div>
@endsection
