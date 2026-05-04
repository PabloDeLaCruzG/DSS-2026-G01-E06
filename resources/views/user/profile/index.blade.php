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

        <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="mt-4 space-y-3">
            @csrf
            <input type="file" name="avatar" accept="image/png,image/jpeg,image/jpg,image/webp"
                   class="w-full text-xs text-text-muted file:mr-3 file:rounded-lg file:border file:border-border file:bg-background file:px-3 file:py-1.5 file:text-text-main file:text-xs">

            @error('avatar')
                <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror

            <button type="submit" class="bg-background border border-border px-4 py-2 rounded-lg text-sm w-full hover:bg-surface transition-colors cursor-pointer">
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

    <form method="POST" action="{{ route('profile.password') }}" class="bg-background p-5 rounded-lg"
          x-data="{
              password: '',
              confirmPassword: '',
              get score() {
                  if (this.password.length === 0) return 0;
                  if (this.password.length < 8) return 1;
                  let hasLettersAndNumbers = /[a-zA-Z]/.test(this.password) && /[0-9]/.test(this.password);
                  let hasSymbols = /[^a-zA-Z0-9]/.test(this.password);
                  if (hasLettersAndNumbers && hasSymbols && this.password.length >= 10) return 3;
                  if (hasLettersAndNumbers || this.password.length >= 10) return 2;
                  return 1;
              },
              get label() {
                  return this.score === 3 ? 'Fuerte' : (this.score === 2 ? 'Media' : 'Débil');
              },
              get colorClass() {
                  return this.score === 3 ? 'bg-green-500' : (this.score === 2 ? 'bg-yellow-500' : 'bg-red-500');
              },
              get textColorClass() {
                  return this.score === 3 ? 'text-green-500' : (this.score === 2 ? 'text-yellow-500' : 'text-red-500');
              }
          }">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-start">
            <div>
                <label class="text-xs text-text-muted mb-1 block">Contraseña actual</label>
                <input type="password" name="current_password" class="w-full bg-surface border border-border px-3 py-2 rounded-lg outline-none focus:border-primary transition-colors">
            </div>
            <div>
                <label class="text-xs text-text-muted mb-1 block">Nueva contraseña</label>
                <input type="password" name="password" x-model="password" class="w-full bg-surface border border-border px-3 py-2 rounded-lg outline-none focus:border-primary transition-colors">
                
                <!-- Password Strength Meter -->
                <div class="mt-2 h-1.5 w-full bg-surface rounded-full overflow-hidden" x-show="password.length > 0" x-transition style="display: none;">
                    <div class="h-full transition-all duration-500 rounded-full" :class="colorClass" :style="`width: ${score * 33.33}%`"></div>
                </div>
                <p class="text-[10px] mt-1 text-right font-medium transition-colors duration-500" x-text="label" :class="textColorClass" x-show="password.length > 0" style="display: none;"></p>
            </div>
            <div>
                <label class="text-xs text-text-muted mb-1 block">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" x-model="confirmPassword" class="w-full bg-surface border px-3 py-2 rounded-lg outline-none transition-colors"
                       :class="confirmPassword.length > 0 ? (password === confirmPassword ? 'border-green-500/50 focus:border-green-500' : 'border-red-500/50 focus:border-red-500') : 'border-border focus:border-primary'">
                <p class="text-[10px] mt-1 text-red-400 text-right font-medium" x-show="confirmPassword.length > 0 && password !== confirmPassword" style="display: none;">No coinciden</p>
            </div>
        </div>

        @if ($errors->has('current_password') || $errors->has('password'))
            <p class="text-red-400 text-xs mt-3">{{ $errors->first('current_password') ?: $errors->first('password') }}</p>
        @endif

        <div class="mt-4 flex justify-end pt-2 border-t border-border/50">
            <button type="submit" class="bg-primary hover:bg-primary-hover text-white transition-colors px-6 py-2 rounded-lg text-sm font-medium cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" 
                    :disabled="password.length > 0 && (score < 2 || password !== confirmPassword)">
                Actualizar Contraseña
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