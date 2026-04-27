@extends('user.layout')

@section('title', 'Mi Perfil')

@section('content')

<h1 class="text-2xl font-bold mb-6">Mi Perfil</h1>

<div class="grid grid-cols-3 gap-6">

    <!-- PERFIL -->
    <div class="bg-surface p-6 rounded-xl text-center">

        <div class="w-24 h-24 mx-auto rounded-full bg-primary flex items-center justify-center text-2xl font-bold">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>

        <h2 class="mt-4 text-lg font-semibold">{{ auth()->user()->name }}</h2>
        <p class="text-text-muted text-sm">{{ auth()->user()->email }}</p>

        @if(auth()->user()->isProfessional())
            <div class="mt-2 inline-flex items-center gap-1 text-xs font-bold px-3 py-1 rounded-full bg-blue-500/20 text-blue-400">
                ✔ Socio Verificado
            </div>
        @elseif(auth()->user()->professionalProfile)
            <div class="mt-2 inline-flex items-center gap-1 text-xs px-3 py-1 rounded-full bg-yellow-500/20 text-yellow-400">
                🕐 Verificación pendiente
            </div>
        @else
            <a href="{{ route('professional.create') }}"
               class="mt-3 inline-block text-xs text-primary hover:underline">
                ¿Eres empresa? Solicita cuenta Pro →
            </a>
        @endif

        <button class="mt-4 bg-background border border-border px-4 py-2 rounded-lg text-sm">
            Editar Avatar
        </button>

    </div>

    <!-- INFO PERSONAL -->
    <div class="col-span-2 bg-surface p-6 rounded-xl">

        <h2 class="mb-4 font-semibold">Información Personal</h2>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="text-xs text-text-muted">Nombre completo</label>
                    <input type="text" name="name"
                        value="{{ old('name', auth()->user()->name) }}"
                        class="w-full mt-1 bg-background border border-border px-3 py-2 rounded-lg">
                </div>

                <div>
                    <label class="text-xs text-text-muted">Correo electrónico</label>
                    <input type="email" name="email"
                        value="{{ old('email', auth()->user()->email) }}"
                        class="w-full mt-1 bg-background border border-border px-3 py-2 rounded-lg">
                </div>

            </div>

            <button class="mt-4 bg-primary px-4 py-2 rounded-lg">
                Guardar Cambios
            </button>

        </form>

    </div>

</div>

<!-- SEGURIDAD -->
<div class="mt-6 bg-surface p-6 rounded-xl">

    <h2 class="mb-4 font-semibold">Seguridad</h2>

    <div class="flex justify-between items-center bg-background p-4 rounded-lg">
        <div>
            <p class="font-medium">Cambiar contraseña</p>
            <p class="text-xs text-text-muted">Actualiza tu contraseña regularmente</p>
        </div>

        <button class="bg-primary px-4 py-2 rounded-lg text-sm">
            Actualizar
        </button>
    </div>

</div>

<!-- ZONA PELIGRO -->
<div class="mt-6 bg-red-500/10 border border-red-500/30 p-6 rounded-xl">

    <h2 class="text-red-400 font-semibold mb-2">Zona de Peligro</h2>

    <p class="text-sm text-text-muted mb-4">
        Una vez elimines tu cuenta, no hay vuelta atrás.
    </p>

    <button class="text-red-400 hover:underline">
        Eliminar mi cuenta definitivamente →
    </button>

</div>

@endsection