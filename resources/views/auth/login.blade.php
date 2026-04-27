@extends('layouts.app')

@section('title', 'Iniciar sesión – GameLink')

@section('content')
<div class="flex items-center justify-center min-h-[70vh]">
    <div class="w-full max-w-md bg-surface border border-border rounded-xl p-8 shadow-lg">

        <!-- Logo + título -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center text-2xl mb-3">
                🎮
            </div>
            <h1 class="text-2xl font-bold text-text-main">Iniciar sesión</h1>
            <p class="text-sm text-text-muted mt-1">Bienvenido de nuevo a GameLink</p>
        </div>

        <!-- Errores generales -->
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-sm rounded-lg px-4 py-3 mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Formulario -->
        <form method="POST" action="/login" class="space-y-5">
            @csrf

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
                    autofocus
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
                    placeholder="••••••••"
                >
                @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botón -->
            <button
                type="submit"
                class="w-full bg-primary hover:bg-primary-hover text-white font-semibold rounded-lg py-2.5 text-sm transition-colors mt-2"
            >
                Iniciar sesión
            </button>
        </form>

        <!-- Enlace a registro -->
        <p class="text-center text-sm text-text-muted mt-6">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" class="text-primary hover:text-accent transition-colors font-medium">
                Regístrate gratis
            </a>
        </p>

    </div>
</div>
@endsection
