@extends('layouts.app')

@section('title', 'Crear cuenta – GameLink')

@section('content')
<div class="flex items-center justify-center min-h-[70vh] py-8">
    <div class="w-full max-w-md bg-surface border border-border rounded-xl p-8 shadow-lg">

        <div class="flex flex-col items-center mb-8">
            <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="6" y1="12" x2="10" y2="12"/>
                    <line x1="8" y1="10" x2="8" y2="14"/>
                    <line x1="15" y1="13" x2="15.01" y2="13"/>
                    <line x1="18" y1="11" x2="18.01" y2="11"/>
                    <rect x="2" y="6" width="20" height="12" rx="2"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-text-main">Crear cuenta</h1>
            <p class="text-sm text-text-muted mt-1">Únete a la comunidad GameLink</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-sm rounded-lg px-4 py-3 mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/register" class="space-y-5">
            @csrf

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

            <button
                type="submit"
                class="w-full bg-primary hover:bg-primary-hover text-white font-semibold rounded-lg py-2.5 text-sm transition-colors mt-2"
            >
                Crear cuenta
            </button>
        </form>

        <p class="text-center text-sm text-text-muted mt-6">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-primary hover:text-accent transition-colors font-medium">
                Inicia sesión
            </a>
        </p>

    </div>
</div>
@endsection
