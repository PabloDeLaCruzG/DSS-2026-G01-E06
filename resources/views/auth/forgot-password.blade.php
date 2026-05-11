@extends('layouts.app')

@section('title', 'Recuperar contraseña – GameLink')

@section('content')
<div class="flex items-center justify-center min-h-[70vh]">
    <div class="w-full max-w-md bg-surface border border-border rounded-xl p-8 shadow-lg">

        <div class="flex flex-col items-center mb-8">
            <h1 class="text-2xl font-bold text-text-main">Recuperar contraseña</h1>
            <p class="text-sm text-text-muted mt-1 text-center">
                Te enviaremos un enlace para restablecer tu contraseña.
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-sm rounded-lg px-4 py-3 mb-6">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('status'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 text-sm rounded-lg px-4 py-3 mb-6">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

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
            </div>

            <button
                type="submit"
                class="w-full bg-primary hover:bg-primary-hover text-white font-semibold rounded-lg py-2.5 text-sm transition-colors mt-2"
            >
                Enviar enlace de recuperación
            </button>
        </form>

        <p class="text-center text-sm text-text-muted mt-6">
            <a href="{{ route('login') }}" class="text-primary hover:text-accent transition-colors font-medium">
                Volver a iniciar sesión
            </a>
        </p>

    </div>
</div>
@endsection
