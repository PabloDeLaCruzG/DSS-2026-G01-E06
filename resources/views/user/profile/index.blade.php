@extends('user.layout')

@section('title', 'Mi Perfil')

@section('content')

<h1 class="text-2xl font-bold mb-6">Mi Perfil</h1>

@if (session('success'))
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 text-sm rounded-lg px-4 py-3 mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-3 gap-6">

    <!-- PERFIL -->
    <div class="bg-surface p-6 rounded-xl text-center">

        <img src="{{ auth()->user()->avatar_url }}"
             alt="Avatar"
             class="w-24 h-24 mx-auto rounded-full object-cover border border-border">

        <h2 class="mt-4 text-lg font-semibold">{{ auth()->user()->name }}</h2>
        <p class="text-text-muted text-sm">{{ auth()->user()->email }}</p>

        <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="mt-4 space-y-3">
            @csrf
            <input type="file" name="avatar" accept="image/png,image/jpeg,image/jpg,image/webp"
                   class="w-full text-xs text-text-muted file:mr-3 file:rounded-lg file:border file:border-border file:bg-background file:px-3 file:py-1.5 file:text-text-main file:text-xs">

            @error('avatar')
                <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror

            <button type="submit" class="bg-background border border-border px-4 py-2 rounded-lg text-sm w-full">
                Editar avatar
            </button>
        </form>

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

    <form method="POST" action="{{ route('profile.password') }}" class="bg-background p-4 rounded-lg">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
            <div>
                <label class="text-xs text-text-muted">Contraseña actual</label>
                <input type="password" name="current_password" class="w-full mt-1 bg-surface border border-border px-3 py-2 rounded-lg">
            </div>
            <div>
                <label class="text-xs text-text-muted">Nueva contraseña</label>
                <input type="password" name="password" class="w-full mt-1 bg-surface border border-border px-3 py-2 rounded-lg">
            </div>
            <div>
                <label class="text-xs text-text-muted">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" class="w-full mt-1 bg-surface border border-border px-3 py-2 rounded-lg">
            </div>
        </div>

        @if ($errors->has('current_password') || $errors->has('password'))
            <p class="text-red-400 text-xs mt-2">{{ $errors->first('current_password') ?: $errors->first('password') }}</p>
        @endif

        <div class="mt-3 flex justify-end">
            <button type="submit" class="bg-primary px-4 py-2 rounded-lg text-sm">
                Actualizar
            </button>
        </div>
    </form>

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