@extends('layouts.app')

@section('title', 'Restablecer contraseña – GameLink')

@section('content')
<div class="flex items-center justify-center min-h-[70vh]">
    <div class="w-full max-w-md bg-surface border border-border rounded-xl p-8 shadow-lg">

        <div class="flex flex-col items-center mb-8">
            <h1 class="text-2xl font-bold text-text-main">Nueva contraseña</h1>
            <p class="text-sm text-text-muted mt-1 text-center">
                Define una contraseña segura para tu cuenta.
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-sm rounded-lg px-4 py-3 mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-sm font-medium text-text-muted mb-1.5">
                    Correo electrónico
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $email) }}"
                    required
                    class="w-full bg-background border @error('email') border-red-500 @else border-border @enderror rounded-lg px-4 py-2.5 text-sm text-text-main placeholder-text-muted outline-none focus:border-primary transition-colors"
                    placeholder="tu@email.com"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-text-muted mb-1.5">
                    Nueva contraseña
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    class="w-full bg-background border @error('password') border-red-500 @else border-border @enderror rounded-lg px-4 py-2.5 text-sm text-text-main placeholder-text-muted outline-none focus:border-primary transition-colors"
                    placeholder="Mínimo 8 caracteres"
                >
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
                Restablecer contraseña
            </button>
        </form>

    </div>
</div>
@endsection
